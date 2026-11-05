@php $post=$getRecord(); @endphp
<article class="ces-news-card">
 <div class="ces-news-cover">@if($post->thumbnail)<img src="{{ asset('uploads/'.$post->thumbnail) }}" alt="{{ $post->title }}" loading="lazy">@else<span>Foto belum ditambahkan</span>@endif</div>
 <div class="ces-news-copy"><span class="ces-studio-status ces-tone-{{ $post->status==='published'?'normal':'warning' }}">{{ $post->status==='published'?'Terbit':'Draf' }}</span><h3>{{ $post->title }}</h3><p>{{ \Illuminate\Support\Str::limit($post->excerpt ?? '',140) }}</p><div class="ces-news-meta"><span>{{ $post->project?->name ?? 'Tanpa project' }}</span><span>{{ $post->published_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y') ?? 'Belum diterbitkan' }}</span>@if(count($post->media_images)>1)<span>{{ count($post->media_images) }} foto</span>@endif</div></div>
</article>
