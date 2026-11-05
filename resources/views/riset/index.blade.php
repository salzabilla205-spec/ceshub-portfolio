<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kabar riset dan project Control Energy System, Teknik Elektro Universitas Sebelas Maret.">
    <title>Kabar Riset | CES</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.landing-style')
    @include('riset.partials.style')
    <link rel="stylesheet" href="{{ asset('css/research-cats.css') }}?v={{ filemtime(public_path('css/research-cats.css')) }}">
    <script defer src="{{ asset('js/research-cats.js') }}?v={{ filemtime(public_path('js/research-cats.js')) }}"></script>
    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
</head>
<body>
<a class="skip" href="#main">Lewati ke konten</a>
@include('partials.landing-header')
<main id="main" class="research-page wrap">
    <div class="research-layout">
        <div>
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a> &nbsp;/&nbsp; Kabar Riset</nav>
    <div class="research-heading">
        <h1>Kabar Riset</h1>
        <p>Kegiatan dan perkembangan riset Control Energy System.</p>
    </div>
            <form class="research-search" method="get" action="{{ route('riset.index') }}" role="search">
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari kabar riset" aria-label="Cari kabar riset" maxlength="120">
                @if($category !== '')<input type="hidden" name="category" value="{{ $category }}">@endif
                <button type="submit" aria-label="Cari kabar riset"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 4.5 4.5"/></svg><span>Cari</span></button>
            </form>
            @error('q')<p class="research-result-count">{{ $message }}</p>@enderror
            <nav class="research-topics" aria-label="Filter topik riset">
                <a href="{{ route('riset.index', array_filter(['q' => $search], fn($value) => $value !== '')) }}" @if($category === '') aria-current="true" @endif>Semua</a>
                @foreach($topics as $topic)
                    <a href="{{ route('riset.index', ['q' => $search, 'category' => $topic->category]) }}" @if($category === $topic->category) aria-current="true" @endif>{{ $topic->category }}</a>
                @endforeach
            </nav>
            @if($search !== '' || $category !== '')
                <h2 class="research-section-title">Hasil pencarian</h2>
                <p class="research-result-count">{{ $projects->total() }} hasil @if($search !== '')untuk “{{ $search }}”@endif @if($category !== '')di {{ $category }}@endif · <a class="research-reset" href="{{ route('riset.index') }}">Hapus filter</a></p>
                @forelse($projects as $project)
                    @include('riset.partials.story', ['project' => $project, 'featured' => false])
                @empty
                    <div class="empty">Tidak ada hasil yang sesuai. Coba kata kunci lain atau hapus filter kategori.</div>
                @endforelse
            @elseif($projects->isNotEmpty())
                <p class="research-result-count">{{ $projects->total() }} kabar · terbaru lebih dulu</p>
                <div class="research-featured {{ $projects->count() === 1 ? 'research-featured--single' : '' }}">
                    @include('riset.partials.story', ['project' => $projects->first(), 'featured' => true])
                    @if($projects->count() > 1)
                        <div>@foreach($projects->getCollection()->slice(1, 3) as $project)@include('riset.partials.story', ['project' => $project, 'featured' => false])@endforeach</div>
                    @endif
                </div>
                @if($projects->count() > 4)
                    <h2 class="research-section-title">Kabar Lainnya</h2>
                    @foreach($projects->getCollection()->slice(4) as $project)
                        @include('riset.partials.story', ['project' => $project, 'featured' => false])
                    @endforeach
                @endif
            @else
                <div class="empty">Belum ada riset yang dipublikasikan. Kabar dari tim CES akan ditampilkan di sini.</div>
            @endif
            @if($projects->hasPages())
                <nav class="pagination" aria-label="Halaman kabar riset">
                    @if($projects->previousPageUrl())<a href="{{ $projects->previousPageUrl() }}" rel="prev">← Sebelumnya</a>@endif
                    <span>{{ $projects->currentPage() }} / {{ $projects->lastPage() }}</span>
                    @if($projects->nextPageUrl())<a href="{{ $projects->nextPageUrl() }}" rel="next">Berikutnya →</a>@endif
                </nav>
            @endif
        </div>
        <aside class="research-aside" aria-label="Informasi riset">
            <section>
                <h2>Topik Riset</h2>
                <ul class="research-topic-list">
                    @forelse($topics as $topic)
                        <li><a href="{{ route('riset.index', ['q' => $search, 'category' => $topic->category]) }}">{{ $topic->category }} <span aria-label="{{ $topic->total }} project">{{ $topic->total }}</span></a></li>
                    @empty
                        <li class="empty-label">Topik akan muncul setelah riset dipublikasikan.</li>
                    @endforelse
                </ul>
            </section>
            <section>
                <h2>Kabar Terbaru</h2>
                <ol class="research-latest">
                    @forelse($latestProjects as $project)
                        <li><a href="{{ route('riset.show', $project->slug) }}">{{ $project->name }}</a>@if($project->published_at)<time datetime="{{ $project->published_at->toDateString() }}">{{ $project->published_at->translatedFormat('d M Y') }}</time>@endif</li>
                    @empty
                        <li class="empty-label">Belum ada kabar terbaru.</li>
                    @endforelse
                </ol>
            </section>
            <div class="research-about"><h2>Tentang CES</h2><p>Riset pemantauan dan kendali energi di Teknik Elektro, Universitas Sebelas Maret.</p><a href="{{ route('tim.index') }}">Kenali tim kami ↗</a></div>
        </aside>
    </div>
</main>
@include('partials.landing-footer')
@include('partials.landing-script')
</body>
</html>
