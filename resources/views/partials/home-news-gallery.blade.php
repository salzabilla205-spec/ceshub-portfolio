@php($newsItems = ($posts->isNotEmpty() ? $posts : $projects)->take(6))
<section class="section pale news-gallery" id="kabar-riset" aria-labelledby="news-title">
    <div class="wrap">
        <div class="section-head"><div><div class="eyebrow">Kabar Riset</div><h2 id="news-title"><span class="home-news-heading-full">Cerita dan perkembangan<br>riset CES.</span><span class="home-news-heading-short">Kabar Riset</span></h2><p>Ikuti kegiatan, temuan, dan kabar terbaru dari tim penelitian.</p></div><div class="news-gallery-actions"><a class="text-link" href="{{ route('riset.index') }}">Semua Kabar Riset ↗</a>@if($newsItems->count()>1)<div class="news-gallery-controls"><button type="button" data-news-prev aria-label="Kabar sebelumnya"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m14 6-6 6 6 6"/></svg></button><button type="button" data-news-next aria-label="Kabar berikutnya"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m10 6 6 6-6 6"/></svg></button></div>@endif</div></div>
        @if($newsItems->isNotEmpty())
        <div class="news-gallery-viewport" role="region" aria-roledescription="carousel" aria-label="Kabar riset CES" tabindex="0"><div class="news-gallery-track">
            @foreach($newsItems as $item)
            @php($isProject = $item instanceof \App\Models\Project)
            @php($itemTitle = $isProject ? $item->name : $item->title)
            <article class="news-gallery-card" data-news-index="{{ $loop->index }}" aria-roledescription="slide" aria-label="{{ $loop->iteration }} dari {{ $newsItems->count() }}">
                @if(count($item->media_images))
                    @include('partials.media-carousel', ['images'=>$item->media_images, 'mediaKey'=>($isProject ? 'project-' : 'post-').$item->id, 'mediaAlt'=>$itemTitle, 'mediaClass'=>'news-gallery-media'])
                @else<div class="news-gallery-placeholder" aria-hidden="true">CES</div>@endif
                <div class="news-gallery-copy"><time datetime="{{ $item->published_at?->toDateString() }}">{{ $item->published_at?->format('d M Y') ?? 'Kabar Riset' }}</time><h3>{{ $itemTitle }}</h3><p>{{ $isProject ? $item->summary : $item->excerpt }}</p>
                    @if($isProject)<a href="{{ route('riset.show',$item) }}">Baca selengkapnya <span aria-hidden="true">→</span></a>@else<button type="button" data-news-open="news-detail-{{ $item->id }}">Baca selengkapnya <span aria-hidden="true">→</span></button>@endif
                </div>
            </article>
            @endforeach
        </div></div>
        <p class="news-gallery-counter" aria-live="off"><span data-news-current>1</span> / {{ $newsItems->count() }}</p>
        @foreach($posts as $post)
        <dialog class="news-gallery-dialog" id="news-detail-{{ $post->id }}" aria-labelledby="news-heading-{{ $post->id }}"><form method="dialog"><button aria-label="Tutup berita">×</button></form><small>{{ $post->published_at?->format('d M Y') }}</small><h2 id="news-heading-{{ $post->id }}">{{ $post->title }}</h2>
            @include('partials.media-carousel', ['images'=>$post->media_images, 'mediaKey'=>'post-'.$post->id, 'mediaAlt'=>$post->title])
            <p>{{ $post->excerpt }}</p><div class="news-gallery-body">{{ strip_tags($post->body ?? '') }}</div>
        </dialog>
        @endforeach
        @else<div class="empty">Belum ada kabar riset yang dipublikasikan. Kabar terbaru dari tim CES akan tampil di sini.</div>@endif
    </div>
</section>
