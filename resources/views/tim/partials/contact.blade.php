<div class="team-contact">
    @if($person->email)
        <a href="mailto:{{ $person->email }}">Email</a>
    @endif
    @if($person->linkedin_url && in_array(strtolower(parse_url($person->linkedin_url, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true))
        <a href="{{ $person->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn ↗</a>
    @endif
</div>
