@php
    $leadMember = $teamMembers->firstWhere('is_supervisor', true)
        ?? $teamMembers->first(fn($member) => str_contains(mb_strtolower($member->name), 'hari maghfiroh'));
    $otherMembers = $teamMembers->reject(fn($member) => $leadMember && $member->id === $leadMember->id);
@endphp
<section class="section wrap home-team" id="tim" aria-labelledby="team-title">
    <div class="section-head"><div><div class="eyebrow">Tim Kami</div><h2 id="team-title">Di balik setiap inovasi.</h2><p>Kenali tim yang mengembangkan riset dan teknologi Control Energy System.</p></div><a class="text-link" href="{{ route('tim.index') }}">Kenali seluruh tim ↗</a></div>
    <div class="home-team-layout">
        @if($leadMember)
        <article class="home-team-lead" data-reveal>
            <div class="home-team-lead-photo">@if($leadMember->photo)<img src="{{ asset('uploads/'.$leadMember->photo) }}" alt="{{ $leadMember->name }}" loading="lazy">@else<span aria-hidden="true">{{ mb_substr($leadMember->name,0,1) }}</span>@endif</div>
            <div class="home-team-lead-copy"><small>Pembimbing riset</small><h3>{{ $leadMember->name }}</h3><p>{{ $leadMember->role }}</p></div>
        </article>
        @endif
        <div class="home-team-members">@forelse($otherMembers as $member)<article class="home-team-member" data-reveal><div class="home-team-member-photo">@if($member->photo)<img src="{{ asset('uploads/'.$member->photo) }}" alt="{{ $member->name }}" loading="lazy">@else<span aria-hidden="true">{{ mb_substr($member->name,0,1) }}</span>@endif</div><h3>{{ $member->name }}</h3><p>{{ $member->role }}</p></article>@empty<p class="empty">Anggota tim belum ditambahkan.</p>@endforelse</div>
    </div>
</section>
