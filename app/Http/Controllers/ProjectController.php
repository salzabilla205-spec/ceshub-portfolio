<?php
namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\Post;
class ProjectController extends Controller
{
    public function home()
    {
        $projects = Project::where('status', 'published')
            ->orderByDesc('published_at')
            ->get();
    
        $teamMembers = TeamMember::orderByDesc('angkatan')
            ->get();
    
        return view('welcome', [
            'projects' => $projects,
            'teamMembers' => $teamMembers,
            'posts' => Post::where('status', 'published')->orderByDesc('published_at')->limit(12)->get(),
        ]);
    }
    
    public function index(\Illuminate\Http\Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);
        $search = trim($filters['q'] ?? '');
        $category = $filters['category'] ?? '';
        $published = Project::where('status', 'published');
        $topics = (clone $published)->whereNotNull('category')->where('category', '!=', '')
            ->selectRaw('category, COUNT(*) as total')->groupBy('category')->orderBy('category')->get();
        $latestProjects = (clone $published)->orderByDesc('published_at')->orderByDesc('id')->limit(3)->get();
        $query = (clone $published)->when($category !== '', fn ($query) => $query->where('category', $category));
        if ($search !== '') {
            $query->matchingResearch($search);
        }

        return view('riset.index', [
            'projects' => $query->orderByDesc('published_at')->orderByDesc('id')->paginate(12)->withQueryString(),
            'topics' => $topics,
            'latestProjects' => $latestProjects,
            'search' => $search,
            'category' => $category,
        ]);
    }
    public function show(Project $project)
    {
        return view('riset.show', [
            'project' => $project,
        ]);
    }
    public function tim()
    {
        $supervisor = TeamMember::where('is_supervisor', true)->first();

        $anggota = TeamMember::with(['projects' => fn ($query) => $query->where('status', 'published')])->where('is_supervisor', false)
            ->orderByDesc('angkatan')
            ->get()
            ->groupBy('angkatan');

        return view('tim.index', [
            'supervisor' => $supervisor,
            'anggotaPerAngkatan' => $anggota,
        ]);
    }
    public function projectTim(string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
    
        $members = $project->teamMembers;
    
        $supervisor = TeamMember::where('is_supervisor', true)->first() ?? $members->firstWhere('is_supervisor', true);
        $anggota = $members->reject(fn ($m) => $m->is_supervisor);
    
        return view('dashboard.tim', [
            'project' => $project,
            'supervisor' => $supervisor,
            'anggota' => $anggota,
        ]);
    }
}