<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>HESS · Monitoring | CES</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    @include('partials.landing-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/lab-monitoring.css') }}?v={{ file_exists(public_path('css/lab-monitoring.css')) ? filemtime(public_path('css/lab-monitoring.css')) : '20261017' }}"><link rel="stylesheet" href="{{ asset('css/hess-monitoring.css') }}?v={{ file_exists(public_path('css/hess-monitoring.css')) ? filemtime(public_path('css/hess-monitoring.css')) : '20261017' }}">
<script defer src="{{ asset('js/dashboard-export.js') }}"></script><script defer src="{{ asset('js/dashboard-history.js') }}?v={{ file_exists(public_path('js/dashboard-history.js')) ? filemtime(public_path('js/dashboard-history.js')) : '20261017' }}"></script>    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
</head>
<body class="hess-page">
    @include('partials.landing-header')
    @php($hessPhoto = file_exists(public_path('uploads/hess.jpg')) ? asset('uploads/hess.jpg') : asset('uploads/hess-battery-testing-inl.jpg'))
    <div class="hess-page-backdrop" aria-hidden="true"><img src="{{ $hessPhoto }}" alt=""></div>
    <div class="app-wrapper wrap">
        
        <main id="app-main" class="hess-main">
            <div class="max-w-7xl mx-auto">
@include('partials.mobile-project-picker')
                
                <nav class="lab-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a> / HESS / Monitoring</nav>
                <div class="header-clean monitoring-heading">
                    <div class="brand">
                        
                        <div>
                            <h1>HESS</h1>
                            <div class="sub">Hybrid Energy Storage System · Baterai utama, superkapasitor, dan kendaraan/motor</div>
                        </div>
                    </div>
                    <div class="actions">
                        <span id="status-badge" class="badge-status offline">
                            <span class="dot"></span> Sistem Offline
                        </span>
                        <span class="update-badge">
                            <i class="fa-regular fa-clock"></i> <strong id="val-updated">-</strong>
                        </span>
                        <button id="export-btn" class="btn-clean success">
                            <i class="fa-solid fa-file-export"></i> Export
                        </button>
                        <button id="pause-resume-btn" class="btn-clean secondary">
                            <i class="fa-solid fa-pause"></i> <span>Pause</span>
                        </button>
                    </div>
                </div>

                
                <div class="hess-layout"><div class="hess-main-column">
                <div class="mb-20">
                    <h2 class="section-clean section-hess">
                        Ringkasan sistem
                        
                    </h2>
                    <div class="grid-6">
                        <div class="stat-clean stat-hess">
                            <div class="top">
                                <span class="label">Tegangan sistem</span>
                            </div>
                            <div class="value"><span id="val-vbatkap">—</span><span class="unit">V</span></div>
                        </div>
                        <div class="stat-clean stat-hess">
                            <div class="top">
                                <span class="label">Arus sistem</span>
                            </div>
                            <div class="value"><span id="val-ibatkap">—</span><span class="unit">A</span></div>
                        </div>
                        <div class="stat-clean stat-hess">
                            <div class="top">
                                <span class="label">Energi HESS</span>
                            </div>
                            <div class="value"><span id="val-whbatkap">—</span><span class="unit">Wh</span></div>
                        </div>
                        <div class="stat-clean stat-kendaraan">
                            <div class="top">
                                <span class="label">Kecepatan</span>
                            </div>
                            <div class="value"><span id="val-speed">—</span><span class="unit">km/h</span></div>
                        </div>
                        <div class="stat-clean stat-kendaraan">
                            <div class="top">
                                <span class="label">Putaran motor</span>
                            </div>
                            <div class="value"><span id="val-rpm">—</span><span class="unit">RPM</span></div>
                        </div>
                        <div class="stat-clean stat-converter">
                            <div class="top">
                                <span class="label">Converter</span>
                            </div>
                            <div class="flex gap-6" style="margin-top: 3px;">
                                <span id="badge-buck" class="conv-clean off">—</span>
                                <span id="badge-boost" class="conv-clean off">—</span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="mb-20">
                    <h2 class="section-clean section-baterai">
                        Baterai utama
                        
                    </h2>
                    <div class="grid-5 mb-12">
                        <div class="stat-clean stat-baterai" >
                            <span class="label">Tegangan baterai</span>
                            <div class="value" ><span id="val-vbat">—</span><span class="unit">V</span></div>
                        </div>
                        <div class="stat-clean stat-baterai" >
                            <span class="label">Arus baterai</span>
                            <div class="value" ><span id="val-ibat">—</span><span class="unit">A</span></div>
                        </div>
                        <div class="stat-clean stat-baterai" >
                            <span class="label">Daya baterai</span>
                            <div class="value" ><span id="val-pbat">—</span><span class="unit">W</span></div>
                        </div>
                        <div class="stat-clean stat-baterai" >
                            <span class="label">SOC baterai</span>
                            <div class="value" ><span id="val-socbat">—</span><span class="unit">%</span></div>
                        </div>
                        <div class="stat-clean stat-baterai" >
                            <span class="label">Energi Baterai</span>
                            <div class="value" ><span id="val-whbat">—</span><span class="unit">Wh</span></div>
                        </div>
                    </div>
                    <div class="grid-3">
                        <div class="chart-clean chart-baterai chart-sm">
                            <div class="chart-title">Tegangan baterai</div>
                            <div style="position: relative; height: 210px;">
                                <canvas id="chartVbat"></canvas>
                            </div>
                        </div>
                        <div class="chart-clean chart-baterai chart-sm">
                            <div class="chart-title">Arus baterai</div>
                            <div style="position: relative; height: 210px;">
                                <canvas id="chartIbat"></canvas>
                            </div>
                        </div>
                        <div class="chart-clean chart-baterai chart-sm">
                            <div class="chart-title">SOC baterai</div>
                            <div style="position: relative; height: 210px;">
                                <canvas id="chartSOCbat"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="mb-20">
                    <h2 class="section-clean section-kapasitor">
                        Superkapasitor
                        
                    </h2>
                    <div class="grid-5 mb-12">
                        <div class="stat-clean stat-kapasitor" >
                            <span class="label">Tegangan superkapasitor</span>
                            <div class="value" ><span id="val-vkap">—</span><span class="unit">V</span></div>
                        </div>
                        <div class="stat-clean stat-kapasitor" >
                            <span class="label">Arus superkapasitor</span>
                            <div class="value" ><span id="val-ikap">—</span><span class="unit">A</span></div>
                        </div>
                        <div class="stat-clean stat-kapasitor" >
                            <span class="label">Daya superkapasitor</span>
                            <div class="value" ><span id="val-pkap">—</span><span class="unit">W</span></div>
                        </div>
                        <div class="stat-clean stat-kapasitor" >
                            <span class="label">SOC superkapasitor</span>
                            <div class="value" ><span id="val-sockap">—</span><span class="unit">%</span></div>
                        </div>
                        <div class="stat-clean stat-kapasitor" >
                            <span class="label">Energi Superkapasitor</span>
                            <div class="value" ><span id="val-whkap">—</span><span class="unit">Wh</span></div>
                        </div>
                    </div>
                    <div class="grid-3">
                        <div class="chart-clean chart-kapasitor chart-sm">
                            <div class="chart-title">Tegangan superkapasitor</div>
                            <div style="position: relative; height: 210px;">
                                <canvas id="chartVkap"></canvas>
                            </div>
                        </div>
                        <div class="chart-clean chart-kapasitor chart-sm">
                            <div class="chart-title">Arus superkapasitor</div>
                            <div style="position: relative; height: 210px;">
                                <canvas id="chartIkap"></canvas>
                            </div>
                        </div>
                        <div class="chart-clean chart-kapasitor chart-sm">
                            <div class="chart-title">SOC superkapasitor</div>
                            <div style="position: relative; height: 210px;">
                                <canvas id="chartSOCKap"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div>
                    <h2 class="section-clean section-kendaraan">
                        Kendaraan & Motor
                        
                    </h2>
                    <div class="grid-3">
                        <div class="chart-clean chart-kendaraan">
                            <div class="chart-title">Kecepatan</div>
                            <div style="position: relative; height: 210px;">
                                <canvas id="chartSpeed"></canvas>
                            </div>
                        </div>
                        <div class="chart-clean chart-kendaraan">
                            <div class="chart-title">Putaran motor</div>
                            <div style="position: relative; height: 210px;">
                                <canvas id="chartRPM"></canvas>
                            </div>
                        </div>
                        <div class="chart-clean chart-kendaraan">
                            <div class="chart-title">Kecepatan dan putaran motor</div>
                            <div style="position: relative; height: 210px;">
                                <canvas id="chartKendaraan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                </div>@include('partials.hess-project')</div>
            </div>
        </main>
    </div>

    
    <div id="exportModal" class="modal-overlay">
        <div class="modal-box">
            <h2><i class="fa-solid fa-file-csv"></i> Export Data CSV</h2>
            <p class="sub">Pilih rentang waktu data yang ingin diekspor</p>
            
            <div class="preset-buttons">
                <button onclick="setPreset('today')">Hari Ini</button>
                <button onclick="setPreset('yesterday')">Kemarin</button>
                <button onclick="setPreset('week')">7 Hari</button>
                <button onclick="setPreset('month')">30 Hari</button>
            </div>

            <form id="exportForm" action="/dashboard/hess/export" method="GET" target="_blank">
                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" id="start_time" name="start_time" value="00:00">
                    </div>
                    <div class="form-group">
                        <label>Jam Akhir</label>
                        <input type="time" id="end_time" name="end_time" value="23:59">
                    </div>
                </div>
                <div class="form-group">
                    <label>Urutan Data</label>
                    <select name="sort">
                        <option value="desc">Terbaru di Atas</option>
                        <option value="asc">Terlama di Atas</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-export"><i class="fa-solid fa-download"></i> Export</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('vendor/lab/chart.umd.js') }}"></script>
<script src="{{ asset('js/hess-monitoring.js') }}?v={{ filemtime(public_path('js/hess-monitoring.js')) }}"></script>
<div class="wrap">@include('partials.dashboard-history', ['historyProject'=>'hess'])</div>
@include('partials.landing-footer')
@include('partials.landing-script')
<script src="{{ asset('js/hess-presentation.js') }}?v={{ filemtime(public_path('js/hess-presentation.js')) }}"></script>
<script src="{{ asset('js/dashboard-chart-style.js') }}"></script>
</body>
</html>
