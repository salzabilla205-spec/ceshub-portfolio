<?php
namespace App\Http\Controllers;
use App\Models\DashboardCredential;
use App\Models\Project;
use App\Models\ProjectNote;
use App\Models\SubmersibleConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DashboardPengaturanController extends Controller
{
    public function index(Request $request, string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        
        if (Auth::guard('dashboard')->check()) {
            $credential = Auth::guard('dashboard')->user();
            if ((int) $credential->project_id !== (int) $project->id) {
                Auth::guard('dashboard')->logout();
                return redirect()->route('dashboard.pengaturan', ['slug' => $slug]);
            }
            $configId = match ($project->monitoring_path) { '/dashboard/smart-water-pump' => 1, '/dashboard/lab-scale' => 2, default => null };
            abort_unless($configId, 404, 'Kontrol perangkat untuk project ini belum tersedia.');
            $submersibleConfig = SubmersibleConfig::find($configId);
            $request->validate(['date' => ['nullable', 'date_format:Y-m-d'], 'edit' => ['nullable', 'integer', 'min:1']]);
            $notes = ProjectNote::where('project_id', $project->id)
                ->when($request->filled('date'), fn ($query) => $query->where('note_date', $request->input('date')))
                ->orderByDesc('note_date')->orderByDesc('id')->paginate(10)->withQueryString();
            $editNote = $request->filled('edit')
                ? ProjectNote::where('project_id', $project->id)->findOrFail($request->integer('edit')) : null;
            return response()->view('dashboard.pengaturan.kontrol', compact('project', 'submersibleConfig', 'notes', 'editNote'))
                ->header('Cache-Control', 'private, no-store, max-age=0');
        }
        
        return response()->view('dashboard.pengaturan.login', compact('project'))
            ->header('Cache-Control', 'private, no-store, max-age=0');
    }
    public function login(Request $request, string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credential = DashboardCredential::where('project_id', $project->id)
            ->where('username', $request->username)
            ->first();
        if (!$credential || !password_verify($request->password, $credential->password_hash)) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ]);
        }
        Auth::guard('dashboard')->login($credential);
        $request->session()->regenerate();
        return redirect()->route('dashboard.pengaturan', ['slug' => $slug]);
    }
    public function logout(string $slug)
    {
        Auth::guard('dashboard')->logout();
        return redirect()->route('dashboard.pengaturan', ['slug' => $slug]);
    }
}
