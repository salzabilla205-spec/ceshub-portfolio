<details class="lab-card dashboard-project-team" data-mobile-card open>
    <summary class="mobile-card-toggle"><h2>{{ $teamTitle }}</h2></summary>
    <div class="mobile-card-content">
    <div class="dashboard-team-list">
        @if($dashboardSupervisor)
            <div class="dashboard-team-lead"><strong>{{ $dashboardSupervisor->name }}</strong><small>Dosen Pembimbing</small></div>
        @endif
        @foreach(($project?->teamMembers ?? collect())->reject(fn ($member) => $member->id === $dashboardSupervisor?->id || $member->is_supervisor) as $member)
            <div><strong>{{ $member->name }}</strong><small>{{ $member->pivot?->role_in_project ?: $member->role }}</small></div>
        @endforeach
    </div>
    @if($project)<a class="lab-link" href="{{ route('dashboard.tim.project', $project->slug) }}">Lihat tim project →</a>@endif
    </div>
</details>
