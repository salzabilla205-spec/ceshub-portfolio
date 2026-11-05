<?php

namespace App\Http\Controllers;

use App\Models\LabSubmersible;
use App\Models\Project;
use App\Models\SubmersibleConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LabMonitoringController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->period($request);
        $project = Project::where('status', 'published')->where(function ($query) {
            $query->where('slug', 'lab-scale')->orWhere('dashboard_url', '/dashboard/lab-scale')
                ->orWhere('dashboard_url', 'like', '%/dashboard/lab-scale');
        })->with(['teamMembers', 'posts' => fn ($query) => $query->where('status', 'published')->latest('published_at')])->first();
        $history = LabSubmersible::whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')->orderByDesc('id')->paginate(25)->withQueryString();

        return view('lab-dashboard', compact('project', 'history', 'from', 'to'));
    }

    public function data()
    {
        $latest = LabSubmersible::latest('created_at')->latest('id')->first();
        $age = $latest ? $latest->created_at->diffInSeconds(now(), false) : null;
        $config = SubmersibleConfig::find(2);

        return response()->json([
            'health' => \App\Services\LabDeviceHealth::read(storage_path('app/ces-ota/health.json')),
            'reading' => $latest ? $this->reading($latest) : null,
            'online' => $age !== null && $age >= -60 && $age <= 300,
            'commands' => ['lampu1' => (int) ($config?->lampu1 ?? 0), 'lampu2' => (int) ($config?->lampu2 ?? 0)],
        ])->header('Cache-Control', 'no-store');
    }

    public function readings(Request $request)
    {
        if ($request->boolean('latest')) {
            $rows = LabSubmersible::orderByDesc('created_at')->orderByDesc('id')->limit(60)->get()->reverse()->values()->map(fn ($row) => $this->reading($row));
            return response()->json(['rows' => $rows])->header('Cache-Control', 'no-store');
        }

        [$from, $to] = $this->period($request);
        $query = LabSubmersible::whereBetween('created_at', [$from, $to]);
        if ($request->boolean('table')) {
            $history = $query->orderByDesc('created_at')->orderByDesc('id')
                ->paginate(25, ['*'], 'page', $request->integer('page', 1))
                ->withPath(route('dashboard.lab-scale'))
                ->appends($request->only(['from', 'to']));

            return response()->json([
                'html' => view('partials.lab-history-rows', compact('history'))->render(),
                'pagination' => view('partials.lab-history-pagination', compact('history'))->render(),
                'period' => ['from' => $from->format('Y-m-d\TH:i'), 'to' => $to->format('Y-m-d\TH:i')],
            ])->header('Cache-Control', 'no-store');
        }
        if ((clone $query)->count() > 10000) {
            throw ValidationException::withMessages(['from' => 'Rentang melebihi 10.000 pembacaan. Pilih rentang lebih pendek untuk grafik dan ekspor.']);
        }

        return response()->json([
            'from' => $from->format('d/m/Y H:i:s'), 'to' => $to->format('d/m/Y H:i:s'),
            'timezone' => config('app.timezone'),
            'rows' => $query->orderBy('created_at')->orderBy('id')->get()->map(fn ($row) => $this->reading($row)),
        ]);
    }

    private function period(Request $request): array
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'to' => ['nullable', 'date_format:Y-m-d\TH:i', 'after_or_equal:from'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);
        $from = $request->filled('from') ? Carbon::parse($request->input('from')) : now()->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfMinute() : now()->endOfDay();
        if ($to->lt($from)) {
            throw ValidationException::withMessages(['to' => 'Waktu akhir harus sesudah waktu mulai.']);
        }
        return [$from, $to];
    }

    private function reading(LabSubmersible $row): array
    {
        return [
            'time' => $row->created_at->format('d/m/Y H:i:s'),
            'label' => $row->created_at->format('d/m H:i'),
            'tegangan' => (float) $row->tegangan,
            'arus' => $row->tegangan > 0 ? round($row->daya / $row->tegangan, 2) : 0,
            'daya' => (float) $row->daya,
            'suhu' => (float) $row->suhu,
            'intensitas_cahaya' => (int) $row->intensitas_cahaya,
            'irradiansi' => $row->estimasi_irradiansi,
            'satuan_irradiansi' => 'W/m2',
            'lampu1' => $row->lampu1 === null ? null : (int) $row->lampu1,
            'lampu2' => $row->lampu2 === null ? null : (int) $row->lampu2,
        ];
    }
}
