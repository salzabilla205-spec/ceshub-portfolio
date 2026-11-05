<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan dan Kendali Project - {{ $project->name }}</title>
    <link rel="stylesheet" href="{{ asset('uploads/ces-tailwind-build.css') }}">
    @include('partials.sidebar-style')
    <style>
        .settings-wrap { max-width: 420px; padding: 40px 24px; }
        .settings-wrap h1 { font-size: 20px; font-weight: 700; margin: 0 0 4px 0; color: #0f172a; }
        .settings-wrap .subtitle { color: #475569; margin: 0 0 24px 0; font-size: 14px; }
        .settings-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .settings-card .card-label { font-size: 14px; color: #475569; margin: 0; }
        .btn-primary {
            width: 100%;
            background: #0f172a;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }
        .btn-primary:hover { background: #1e293b; }
        .btn-logout {
            width: 100%;
            background: none;
            border: none;
            color: #64748b;
            font-size: 14px;
            text-decoration: underline;
            cursor: pointer;
            padding: 8px 0;
            margin-top: 24px;
        }
        .back-link {
            display: block;
            font-size: 14px;
            color: #64748b;
            text-decoration: none;
            margin-top: 8px;
        }
        .back-link:hover { color: #0f172a; }
        .settings-grid.settings-grid-pv { grid-template-columns: minmax(0, 1fr); }
        .settings-grid-pv > .settings-controls { position: static; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .settings-grid-pv .btn-logout, .settings-grid-pv .back-link { margin-top: 0; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/project-journal.css') }}?v={{ filemtime(public_path('css/project-journal.css')) }}">
</head>
<body>
    @include('partials.sidebar')

    <div class="app-main" id="app-main">
        <div class="settings-wrap">
            <h1>Pengaturan dan Kendali Project</h1>
            <p class="subtitle">{{ $project->name }}</p>
            <div class="settings-grid {{ $project->slug === 'lab-scale' ? 'settings-grid-pv' : '' }}">
            <div class="settings-controls">
    @if($project->slug !== 'lab-scale')
    @if(config('monitoring-source.url') === 'https://cespv.com' && request()->getHost() === 'ceshub.id')
    <div class="settings-card">
        <p class="card-label">Kontrol perangkat</p>
        <p style="font-size:14px;margin:0;color:#475569">Perangkat masih terhubung ke situs lama. Gunakan halaman kontrol di sana untuk mengubah lampu atau pompa.</p>
        <a class="btn-primary" style="text-align:center;text-decoration:none;box-sizing:border-box" href="https://cespv.com/dashboard/pengaturan/{{ $project->slug }}">Buka kontrol perangkat</a>
    </div>
    @else
    <div class="settings-card">
        <p class="card-label">{{ $project->monitoring_path === '/dashboard/smart-water-pump' ? 'Kontrol Pompa' : 'Kontrol Lampu PV' }}</p>
        <p style="margin:0; font-size:14px; font-weight:600; color: {{ $submersibleConfig && $submersibleConfig->active_button ? '#16a34a' : '#dc2626' }};">
            Status saat ini: {{ $submersibleConfig && $submersibleConfig->active_button ? 'Nyala' : 'Mati' }}
        </p>
        @if($project->monitoring_path === '/dashboard/smart-water-pump')
        <form method="POST" action="{{ route('pumpActivation', $project->slug) }}">
            @csrf
            <button type="submit" class="btn-primary">
                Nyalakan / Matikan Pompa
            </button>
        </form>
        @else
        <div class="ces-lamp-controls">@foreach([1,2] as $lamp)<form method="POST" action="{{ route('lab-scale.toggle', $lamp) }}" data-device-control>@csrf<button type="submit" class="btn-primary">Lampu {{ $lamp }}: {{ $submersibleConfig?->{'lampu'.$lamp} ? 'ON' : 'OFF' }} · Ubah</button></form>@endforeach</div><p data-control-message role="status"></p>
        @endif
    </div>

    @endif

    @endif

            <form method="POST" action="{{ route('dashboard.pengaturan.logout', $project->slug) }}">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>

            <a href="{{ url($project->monitoring_path) }}" class="back-link">
                &larr; Kembali ke Monitoring
            </a>
            </div>
            @include('dashboard.pengaturan.catatan')
            </div>
        </div>
    </div>
<script src="{{ asset('js/project-controls.js') }}" defer></script>
</body>
</html>
