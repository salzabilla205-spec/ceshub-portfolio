<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Monitoring Kumbung Jamur | CES</title>
    @include('partials.landing-style')
    <link rel="stylesheet" href="{{ asset('css/lab-monitoring.css') }}?v={{ file_exists(public_path('css/lab-monitoring.css')) ? filemtime(public_path('css/lab-monitoring.css')) : '20261017' }}">
    <link rel="stylesheet" href="{{ asset('css/jamur-monitoring.css') }}?v={{ file_exists(public_path('css/jamur-monitoring.css')) ? filemtime(public_path('css/jamur-monitoring.css')) : '20261017' }}">
    <script defer src="{{ asset('vendor/lab/chart.umd.js') }}"></script>
    <script defer src="{{ asset('js/jamur-monitoring.js') }}"></script>
    <script defer src="{{ asset('js/lab-export.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
</head>
<body class="lab-body jamur-page">
@include('partials.landing-header')
@php($photo = file_exists(public_path('uploads/jamur-kumbung-aktual.jpg')) ? asset('uploads/jamur-kumbung-aktual.jpg') : (file_exists(public_path('uploads/jamur-kumbung.jpg')) ? asset('uploads/jamur-kumbung.jpg') : ($project?->thumbnail ? asset('uploads/' . $project->thumbnail) : 'https://images.unsplash.com/photo-1762889278403-1d1a57d9a587?auto=format&fit=crop&w=1600&q=85')))
<div class="jamur-page-backdrop" aria-hidden="true"><img src="{{ $photo }}" alt=""></div>
<main class="lab-page wrap" id="lab-app" data-temperature-min="{{ \App\Http\Controllers\jamurDashboardController::SUHU_MIN }}" data-temperature-max="{{ \App\Http\Controllers\jamurDashboardController::SUHU_MAX }}" data-humidity-min="{{ \App\Http\Controllers\jamurDashboardController::RH_MIN }}" data-humidity-max="{{ \App\Http\Controllers\jamurDashboardController::RH_MAX }}" data-vendor="{{ asset('vendor/lab') }}" data-live="{{ route('jamur.monitoring') }}" data-readings="{{ route('jamur.readings') }}" >
@include('partials.mobile-project-picker')
    <nav class="lab-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a> / Jamur / Monitoring</nav>
    <div class="lab-heading monitoring-heading"><div><h1>Monitoring Kumbung Jamur</h1><p>Pemantauan suhu, kelembapan, dan status kondisi kumbung.</p></div><div class="lab-connection"><strong id="connection">Memeriksa koneksi…</strong><small id="last-reading">Menunggu pembacaan perangkat</small></div></div>
    @if($errors->any())<p class="lab-error" role="alert">{{ $errors->first() }}</p>@endif
    <div class="lab-grid" id="monitoring">
        <div class="lab-main-column">
            <section class="lab-card lab-overview">
                <h2 class="jamur-overview-title">Kondisi kumbung</h2>
                <div class="lab-metrics jamur-metrics"><div class="jamur-temperature"><span class="jamur-metric-label"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 14.5V5a3 3 0 0 1 6 0v9.5a5 5 0 1 1-6 0Z"/><path d="M12 8v10"/></svg>Suhu</span><strong><span data-reading="suhu">—</span> °C</strong></div><div class="jamur-moisture"><span class="jamur-metric-label"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3s-7 8-7 12a7 7 0 0 0 14 0c0-4-7-12-7-12Z"/></svg>Kelembapan</span><strong><span data-reading="kelembapan">—</span> % RH</strong></div></div>
            </section>
            <section class="lab-card jamur-condition-status"><h2>Status kondisi kumbung</h2><div class="jamur-summary"><span>Suhu</span><strong id="temperature-state">Belum tersedia</strong></div><div class="jamur-summary"><span>Kelembapan</span><strong id="humidity-state">Belum tersedia</strong></div><p class="lab-hint jamur-normal-range">Rentang normal: {{ \App\Http\Controllers\jamurDashboardController::SUHU_MIN }}–{{ \App\Http\Controllers\jamurDashboardController::SUHU_MAX }} °C · {{ \App\Http\Controllers\jamurDashboardController::RH_MIN }}–{{ \App\Http\Controllers\jamurDashboardController::RH_MAX }} % RH</p><small id="summary-age"></small></section>
            <div class="jamur-charts">
                <section class="lab-card"><h2>Grafik suhu</h2><div class="lab-chart"><canvas id="temperature-chart" aria-label="Grafik suhu" role="img"></canvas><span class="sensor-chart-empty">Belum ada pembacaan</span></div></section>
                <section class="lab-card"><h2>Grafik kelembapan</h2><div class="lab-chart"><canvas id="humidity-chart" aria-label="Grafik kelembapan" role="img"></canvas><span class="sensor-chart-empty">Belum ada pembacaan</span></div></section>
            </div>
            <p id="chart-message" role="status"></p>
        </div>
        <aside class="lab-side-column">
            @include('partials.project-research-card', ['researchTitle'=>'Kabar Riset Jamur'])
            @include('partials.dashboard-project-team', ['teamTitle' => 'Tim Jamur'])
        </aside>
    </div>
    <section class="lab-card lab-history" id="history">
        <div class="lab-card-heading"><h2>Riwayat pembacaan</h2><details class="lab-export-menu"><summary class="lab-button">Export <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></summary><div>@foreach(['csv'=>'CSV','xlsx'=>'Excel (.xlsx)','pdf'=>'PDF','png'=>'PNG','docx'=>'Word (.docx)'] as $format=>$label)<button type="button" data-export="{{ $format }}">{{ $label }}</button>@endforeach<small>Grafik + seluruh tabel dalam rentang</small></div></details></div>
        <form class="lab-filters" method="get" action="{{ route('dashboard.jamur') }}#history"><label>Dari<input type="datetime-local" name="from" value="{{ $from->format('Y-m-d\TH:i') }}" required></label><label>Sampai<input type="datetime-local" name="to" value="{{ $to->format('Y-m-d\TH:i') }}" required></label><button class="lab-button">Terapkan</button><a href="{{ route('dashboard.jamur') }}#history">Hari ini</a><small>Zona waktu: {{ config('app.timezone') }}</small></form>
        <p id="export-message" role="status" aria-live="polite"></p>
        <div class="lab-table-scroll"><table><thead><tr><th>Waktu</th><th>Suhu (°C)</th><th>Kelembapan (% RH)</th></tr></thead><tbody>
            @forelse($history as $row)
                <tr><td>{{ $row->created_at->format('d/m/Y H:i:s') }}</td><td>{{ $row->suhu_node1 ?? '—' }}</td><td>{{ $row->kelembaban_node1 ?? '—' }}</td></tr>
            @empty
                <tr><td colspan="3" class="lab-empty">{{ $databaseError ? 'Riwayat belum dapat dimuat.' : 'Belum ada pembacaan dalam rentang ini.' }}</td></tr>
            @endforelse
        </tbody></table></div>
        <div class="lab-pagination"><span>{{ $history->firstItem() ?? 0 }}–{{ $history->lastItem() ?? 0 }} dari {{ $history->total() }} pembacaan · 25 per halaman</span><nav aria-label="Halaman riwayat">@if($history->onFirstPage())<span>@include('partials.ui-icon', ['icon'=>'left'])</span>@else<a href="{{ $history->previousPageUrl() }}#history" aria-label="Halaman sebelumnya">@include('partials.ui-icon', ['icon'=>'left'])</a>@endif @for($page=max(1,$history->currentPage()-2);$page<=min($history->lastPage(),$history->currentPage()+2);$page++)<a href="{{ $history->url($page) }}#history" @if($page===$history->currentPage()) aria-current="page" @endif>{{ $page }}</a>@endfor @if($history->hasMorePages())<a href="{{ $history->nextPageUrl() }}#history" aria-label="Halaman berikutnya">@include('partials.ui-icon', ['icon'=>'right'])</a>@else<span>@include('partials.ui-icon', ['icon'=>'right'])</span>@endif</nav></div>
    </section>
</main>
@include('partials.landing-footer')
@include('partials.landing-script')
</body>
</html>
