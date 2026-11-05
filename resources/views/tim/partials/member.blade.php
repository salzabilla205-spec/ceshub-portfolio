<article class="team-card" data-team-member="{{ $member->id }}">
    @include('tim.partials.photo', ['person' => $member])
    <h3>{{ $member->name }}</h3>
    <p class="team-role">{{ $member->role }}</p>
    <span class="team-status {{ $member->is_active ? 'team-status--active' : '' }}">{{ $member->is_active ? 'Aktif' : 'Sudah Lulus' }}</span>
    <details class="team-detail">
        <summary>Detail</summary>
        <h4>Kontribusi Project</h4>
        @if($member->projects->isNotEmpty())
            <ul>
                @foreach($member->projects as $project)
                    <li><a href="{{ route('riset.show', $project->slug) }}">{{ $project->name }}</a>@if($project->pivot->role_in_project)<span>{{ $project->pivot->role_in_project }}</span>@endif</li>
                @endforeach
            </ul>
        @else
            <p>Belum ada kontribusi project yang dipublikasikan.</p>
        @endif
        @include('tim.partials.contact', ['person' => $member])
    </details>
</article>
