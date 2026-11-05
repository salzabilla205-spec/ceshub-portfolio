<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Living Lab | CES</title>
    @include('partials.landing-style')
</head>
<body>
@include('partials.landing-header')
<main class="wrap" style="padding:100px 0 64px;min-height:calc(100svh - 80px)">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a> / Living Lab</nav>
    <h1>Living Lab</h1>
    <div class="empty" role="status">
        <h2 style="font-size:20px;color:var(--ink);margin-top:0">Belum ada data sensor</h2>
        <p>Belum tersedia rekaman pengukuran hingga {{ $cutoff->format('d/m/Y H:i') }}.</p>
        <p>Dashboard akan tersedia setelah data pada periode tersebut ditambahkan.</p>
        <a class="text-link" href="{{ route('dashboard.living-lab') }}">Muat ulang ↻</a>
    </div>
</main>
@include('partials.landing-footer')
@include('partials.landing-script')
</body>
</html>
