<a class="about-team-preview" href="{{ route('tim.index') }}" aria-label="Lihat seluruh tim CES">
    <span class="about-team-avatars" aria-hidden="true">@foreach($teamMembers->sortByDesc('is_supervisor')->take(4) as $member)<span>@if($member->photo)<img src="{{ asset('uploads/'.$member->photo) }}" alt="" loading="lazy">@else{{ mb_substr($member->name,0,1) }}@endif</span>@endforeach</span>
    <span class="about-team-label"><strong>Anggota Tim CES</strong><small>Lihat tim →</small></span>
    @include('partials.ui-icon', ['icon'=>'right'])
</a>
