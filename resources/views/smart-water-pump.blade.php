<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Smart Water Pump | CES</title>
@include('partials.landing-style')
<link rel="stylesheet" href="{{ asset('css/lab-monitoring.css') }}?v={{ file_exists(public_path('css/lab-monitoring.css')) ? filemtime(public_path('css/lab-monitoring.css')) : '20261017' }}"><link rel="stylesheet" href="{{ asset('css/water-pump.css') }}?v={{ file_exists(public_path('css/water-pump.css')) ? filemtime(public_path('css/water-pump.css')) : '20261017' }}">
<script defer src="{{ asset('vendor/lab/chart.umd.js') }}"></script><script defer src="{{ asset('js/water-pump.js') }}"></script>
<script defer src="{{ asset('js/dashboard-export.js') }}"></script>    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
</head>
<body class="lab-body">
@include('partials.landing-header')
<main class="lab-page wrap" id="water-pump-app" data-live="{{ route('water-pump.monitoring') }}" data-readings="{{ route('water-pump.readings') }}">
@include('partials.mobile-project-picker')
<nav class="lab-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a> / Smart Water Pump / Monitoring</nav>
<div class="lab-heading monitoring-heading"><div><h1>Smart Water Pump</h1><p>Pemantauan debit air dan kelistrikan pompa.</p>@if($project)<a class="lab-link" href="{{ route('dashboard.pengaturan', $project->slug) }}" aria-label="Masuk ke kontrol project"><span class="monitoring-control-full" aria-hidden="true">Masuk ke kontrol project →</span><span class="monitoring-control-short" aria-hidden="true">Kontrol @include('partials.ui-icon', ['icon'=>'external'])</span></a>@endif</div><div class="lab-connection"><strong id="connection">Memeriksa koneksi…</strong><small id="last-reading"></small></div></div>
@if($errors->any())<p class="lab-error" role="alert">{{ $errors->first() }}</p>@endif
<div class="lab-grid"><div class="lab-main-column">
<section class="lab-card lab-overview">
<div class="pump-overview"><div><h2>Aliran air</h2><p>Debit saat ini</p><strong><span data-reading="debit">—</span> <small>L/min</small></strong><p id="pump-state">Status pompa belum tersedia</p></div><div class="pump-volume"><span>Volume hari ini</span><strong><span data-reading="volume_harian">—</span> <small>L</small></strong></div></div>
<div class="lab-metrics"><div>Daya<strong><span data-reading="daya">—</span> W</strong></div><div>Tegangan<strong><span data-reading="tegangan">—</span> V</strong></div><div>Energi hari ini<strong><span data-reading="energi_harian">—</span> Wh</strong></div><div>Debit<strong><span data-reading="debit">—</span> L/min</strong></div></div>
</section>
<div class="pump-charts"><section class="lab-card"><h2>Debit air</h2><div class="lab-chart"><canvas id="flow-chart" aria-label="Grafik debit air" role="img"></canvas></div></section>
<section class="lab-card"><h2>Daya listrik</h2><div class="lab-chart"><canvas id="power-chart" aria-label="Grafik daya listrik" role="img"></canvas></div></section><section class="lab-card"><h2>Tegangan</h2><div class="lab-chart"><canvas id="pump-voltage-chart" aria-label="Grafik tegangan pompa" role="img"></canvas></div></section><section class="lab-card"><h2>Energi hari ini</h2><div class="lab-chart"><canvas id="pump-energy-chart" aria-label="Grafik energi harian pompa" role="img"></canvas></div></section></div><p id="chart-message" role="status"></p>
</div><aside class="lab-side-column">
<section class="lab-card pump-reading-summary"><h2>Ringkasan pembacaan</h2><p class="lab-hint">Debit menunjukkan aliran air yang dilaporkan sensor.</p><div class="pump-summary"><span>Aliran air</span><strong id="flow-state">Belum tersedia</strong></div><div class="pump-summary"><span>Pembaruan data</span><strong>15 detik</strong></div><small id="summary-age"></small></section>
@include('partials.project-research-card', ['researchTitle'=>'Kabar Riset'])
@include('partials.dashboard-project-team', ['teamTitle' => 'Tim Smart Water Pump'])
</aside></div>
<section class="lab-card lab-history" id="history"><div class="lab-card-heading"><h2>Riwayat pembacaan</h2><button type="button" class="lab-button" id="export-csv">Export CSV</button></div>
<form class="lab-filters" method="get" action="{{ route('dashboard.living-lab') }}#history"><label>Dari<input type="datetime-local" name="from" value="{{ $from->format('Y-m-d\TH:i') }}" required></label><label>Sampai<input type="datetime-local" name="to" value="{{ $to->format('Y-m-d\TH:i') }}" required></label><button class="lab-button">Terapkan</button><a href="{{ route('dashboard.living-lab') }}#history">Hari ini</a><small>Zona waktu: {{ config('app.timezone') }}</small></form><p id="export-message" role="status"></p>
<div class="lab-table-scroll"><table><thead><tr><th>Waktu</th><th>Debit (L/min)</th><th>Volume harian (L)</th><th>Daya (W)</th><th>Tegangan (V)</th><th>Energi harian (Wh)</th></tr></thead><tbody>@forelse($history as $row)<tr><td>{{ $row->created_at->format('d/m/Y H:i:s') }}</td>@foreach(['debit','volume_harian','daya','tegangan','energi_harian'] as $field)<td>{{ $row->$field ?? '—' }}</td>@endforeach</tr>@empty<tr><td colspan="6" class="lab-empty">Belum ada pembacaan dalam rentang ini.</td></tr>@endforelse</tbody></table></div>
<div class="lab-pagination"><span>{{ $history->firstItem() ?? 0 }}–{{ $history->lastItem() ?? 0 }} dari {{ $history->total() }} pembacaan · 25 per halaman</span><nav aria-label="Halaman riwayat">@if(!$history->onFirstPage())<a href="{{ $history->previousPageUrl() }}#history" aria-label="Halaman sebelumnya">@include('partials.ui-icon', ['icon'=>'left'])</a>@endif @for($page=max(1,$history->currentPage()-2);$page<=min($history->lastPage(),$history->currentPage()+2);$page++)<a href="{{ $history->url($page) }}#history" @if($page===$history->currentPage()) aria-current="page" @endif>{{ $page }}</a>@endfor @if($history->hasMorePages())<a href="{{ $history->nextPageUrl() }}#history" aria-label="Halaman berikutnya">@include('partials.ui-icon', ['icon'=>'right'])</a>@endif</nav></div>
</section></main>
@include('partials.landing-footer')
@include('partials.landing-script')
</body></html>