<div class="team-photo">
    <span aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($person->name, 0, 1)) }}</span>
    @if($person->photo)
        <img src="{{ asset('uploads/' . $person->photo) }}" alt="{{ $person->name }}" width="320" height="320" loading="lazy" onerror="this.hidden = true">
    @endif
</div>
