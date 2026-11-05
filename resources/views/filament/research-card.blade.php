@php $entry = $getRecord(); @endphp
<article class="ces-news-card">
    <div class="ces-news-cover">
        @if($entry['thumbnail'])
            <img src="{{ asset('uploads/'.$entry['thumbnail']) }}" alt="{{ $entry['title'] }}" loading="lazy">
        @else
            <span>Foto belum ditambahkan</span>
        @endif
    </div>
    <div class="ces-news-copy">
        <span class="ces-studio-status ces-tone-{{ $entry['status'] === 'published' ? 'normal' : 'warning' }}">{{ $entry['status'] === 'published' ? 'Terbit' : 'Draf' }}</span>
        <h3>{{ $entry['title'] }}</h3>
        <p>{{ \Illuminate\Support\Str::limit($entry['summary'] ?? '', 140) }}</p>
        <div class="ces-news-meta">
            @if($entry['team_count'] !== null)<span>{{ $entry['team_count'] }} anggota</span>@endif
            @if($entry['category'])<span class="ces-news-category">{{ $entry['category'] }}</span>@endif
            <span>{{ $entry['published_at'] ? \Illuminate\Support\Carbon::parse($entry['published_at'])->timezone('Asia/Jakarta')->translatedFormat('d M Y') : 'Belum diterbitkan' }}</span>
        </div>
    </div>
</article>
