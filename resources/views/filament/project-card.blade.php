@php $project = $getRecord(); @endphp
<article class="ces-news-card">
    <div class="ces-news-cover">
        @if($project->thumbnail)
            <img src="{{ asset('uploads/'.$project->thumbnail) }}" alt="{{ $project->name }}" loading="lazy">
        @else
            <span>Foto belum ditambahkan</span>
        @endif
    </div>
    <div class="ces-news-copy">
        <span class="ces-studio-status ces-tone-{{ $project->status === 'published' ? 'normal' : 'warning' }}">{{ $project->status === 'published' ? 'Terbit' : 'Draf' }}</span>
        <h3>{{ $project->name }}</h3>
        <p>{{ \Illuminate\Support\Str::limit($project->summary ?? '', 140) }}</p>
        <div class="ces-news-meta">
            <span>{{ $project->team_members_count }} anggota</span>
            @if($project->category)<span class="ces-news-category">{{ $project->category }}</span>@endif
            <span>{{ $project->published_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y') ?? 'Belum diterbitkan' }}</span>
        </div>
    </div>
</article>
