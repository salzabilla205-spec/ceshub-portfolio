@php
    $allProjects = [
        'living-lab'   => ['label' => 'Smart Water Pump'],
        'lab-scale'    => ['label' => 'Digital Twin PV'],
        'jamur'        => ['label' => 'Monitoring Kumbung Jamur'],
        'digital-twin' => ['label' => 'Digital Twin Prof Nizam'],
        'hess'         => ['label' => 'HESS'],
    ];

    $currentProject = null;
    if (
        request()->is('dashboard') ||
        request()->is('dashboard/pengaturan*') ||
        request()->is('riset/living-lab') ||
        request()->is('dashboard/tim/living-lab')
    ) {
        $currentProject = 'living-lab';
    } elseif (
        request()->is('dashboard/lab-scale*') ||
        request()->is('riset/lab-scale') ||
        request()->is('dashboard/tim/lab-scale')
    ) {
        $currentProject = 'lab-scale';
    } elseif (
        request()->is('dashboard/jamur*') ||
        request()->is('riset/jamur') ||
        request()->is('dashboard/tim/jamur')
    ) {
        $currentProject = 'jamur';
    } elseif (
        request()->is('dashboard/digital-twin*') ||
        request()->is('riset/digital-twin') ||
        request()->is('dashboard/tim/digital-twin')
    ) {
        $currentProject = 'digital-twin'; 
    } elseif (
        request()->is('dashboard/hess*') ||
        request()->is('riset/hess*') ||
        request()->is('dashboard/tim/hess')
    ) {
        $currentProject = 'hess';
    }
    if (request()->is('dashboard/pengaturan/*') && isset($project)) {
        $currentProject = match ($project->monitoring_path) {
            '/dashboard/lab-scale' => 'lab-scale',
            '/dashboard/smart-water-pump' => 'living-lab',
            default => $currentProject,
        };
    }
@endphp

<aside class="app-sidebar" id="app-sidebar">
    <div class="logo-row" id="app-logo-row">
        <a href="{{ url('/') }}" style="display:flex; align-items:center; gap:10px; text-decoration:none; overflow:hidden;">
            <img src="{{ asset('assets/logo/ces-logo.png') }}" alt="Logo CES" style="width:32px; height:32px; flex-shrink:0;">
            <span class="logo-text" style="overflow:hidden;">
                <span style="display:block; font-size:15px; font-weight:700; color:#0f172a; white-space:nowrap;">CES</span>
                <span style="display:block; font-size:11px; color:#94a3b8; white-space:nowrap;">Control Energy System</span>
            </span>
        </a>
        <button id="app-sidebar-toggle" aria-label="Kecilkan menu" style="border:none; background:transparent; cursor:pointer; color:#475569; align-items:center; justify-content:center; width:32px; height:32px; flex-shrink:0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    <nav style="padding-top:8px; overflow-y:auto;">
        <a href="{{ url('/') }}" class="nav-link">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/></svg>
            <span>Beranda</span>
        </a>
        <a href="{{ route('riset.index') }}" class="nav-link">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3h6M10 3v5.5L4.5 18a2 2 0 001.7 3h11.6a2 2 0 001.7-3L14 8.5V3"/></svg>
            <span>Kabar Riset</span>
        </a>
        <a href="{{ route('tim.index') }}" class="nav-link">
            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20v-1a4 4 0 00-4-4H7a4 4 0 00-4 4v1M10 11a4 4 0 100-8 4 4 0 000 8zM23 20v-1a4 4 0 010 7.75"/></svg>
            <span>Tim</span>
        </a>

        @if ($currentProject)
            @php $otherProjects = collect($allProjects)->except($currentProject); @endphp
            <button type="button" class="nav-section-toggle" id="other-projects-toggle">
                <span style="display:flex; align-items:center; gap:10px;">
                    <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="12" rx="1"/><path stroke-linecap="round" d="M8 20h8M12 16v4"/></svg>
                    <span>Project Lain</span>
                </span>
                <svg class="chevron" id="other-projects-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/>
                </svg>
            </button>
            <div class="dashboard-accordion" id="other-projects-accordion">
                <div class="project-submenu open">
                    @foreach ($otherProjects as $slug => $proj)
                        <a href="{{ url('/riset/'.$slug) }}" class="nav-sub-link2">{{ $proj['label'] }}</a>
                    @endforeach
                </div>
            </div>

            <div class="nav-section-label" style="padding:14px 16px 6px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.04em;">
                Dashboard — {{ ucwords(str_replace('-', ' ', $currentProject)) }}
            </div>
            <div class="dashboard-accordion open" id="dashboard-accordion">
                <div class="project-submenu open" id="submenu-{{ $currentProject }}">
                    @if ($currentProject === 'living-lab')
                        <a href="{{ url('/riset/living-lab') }}" class="nav-sub-link2">Penjelasan</a>
                        <a href="{{ route('dashboard.living-lab') }}" class="nav-sub-link2">Monitoring</a>
                        <a href="{{ url('/dashboard/pengaturan') }}" class="nav-sub-link2">Pengaturan</a>
                        <a href="{{ route('dashboard.tim.project', 'living-lab') }}" class="nav-sub-link2">Tim</a>
                    @elseif ($currentProject === 'lab-scale')
                        <span class="nav-sub-link2" style="opacity:.45; cursor:default;">Penjelasan</span>
                        <a href="{{ url('/dashboard/lab-scale') }}" class="nav-sub-link2">Monitoring</a>
                        <span class="nav-sub-link2" style="opacity:.45; cursor:default;">Pengaturan</span>
                        <a href="{{ route('dashboard.tim.project', 'lab-scale') }}" class="nav-sub-link2">Tim</a>
                    @elseif ($currentProject === 'jamur')
                        <a href="{{ url('/riset/jamur') }}" class="nav-sub-link2">Penjelasan</a>
                        <a href="{{ url('/dashboard/jamur') }}" class="nav-sub-link2">Monitoring</a>
                        <span class="nav-sub-link2" style="opacity:.45; cursor:default;">Pengaturan</span>
                        <a href="{{ route('dashboard.tim.project', 'jamur') }}" class="nav-sub-link2">Tim</a>
                    @elseif ($currentProject === 'digital-twin')
                        <a href="{{ url('/riset/digital-twin') }}#ac" class="nav-sub-link2" onclick="if(typeof switchTab === 'function') { switchTab('ac'); }">Sistem AC 3-Fasa</a>
                        <a href="{{ url('/riset/digital-twin') }}#dc" class="nav-sub-link2" onclick="if(typeof switchTab === 'function') { switchTab('dc'); }">Sistem DC PZEM-017</a>
                        <a href="{{ url('/riset/digital-twin') }}#bms" class="nav-sub-link2" onclick="if(typeof switchTab === 'function') { switchTab('bms'); }">BMS Daly R24PK</a>
                    @elseif ($currentProject === 'hess')
                        <a href="{{ url('/riset/hess') }}" class="nav-sub-link2">Penjelasan</a>
                        <a href="{{ url('/dashboard/hess') }}" class="nav-sub-link2">Monitoring</a>
                        <span class="nav-sub-link2" style="opacity:.45; cursor:default;">Pengaturan</span>
                        <a href="{{ route('dashboard.tim.project', 'hess') }}" class="nav-sub-link2">Tim</a>
                    @endif
                </div>
            </div>
        @else
            <button type="button" class="nav-section-toggle" id="dashboard-toggle">
                <span style="display:flex; align-items:center; gap:10px;">
                    <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="12" rx="1"/><path stroke-linecap="round" d="M8 20h8M12 16v4"/></svg>
                    <span>Project</span>
                </span>
                <svg class="chevron" id="dashboard-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/>
                </svg>
            </button>
            <div class="dashboard-accordion" id="dashboard-accordion">
                <div class="project-submenu open">
                    @foreach ($allProjects as $slug => $proj)
                        <a href="{{ url('/riset/'.$slug) }}" class="nav-sub-link2">{{ $proj['label'] }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    </nav>
</aside>

<div id="app-sidebar-overlay"></div>

<header class="app-topbar" id="app-topbar">
    <button class="app-hamburger" id="app-hamburger" aria-label="Buka menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    <div class="app-topbar-brand" style="align-items:center; gap:10px;">
        <img src="{{ asset('assets/logo/ces-logo.png') }}" alt="Logo CES" style="width:32px; height:32px;">
        <div>
            <h1 style="margin:0; font-size:15px; font-weight:700; color:#0f172a; line-height:1.2;">CES</h1>
            <p style="margin:0; font-size:11px; color:#94a3b8; line-height:1.2;">Control Energy System</p>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar   = document.getElementById('app-sidebar');
        const overlay   = document.getElementById('app-sidebar-overlay');
        const hamburger = document.getElementById('app-hamburger');

        function openMobile() {
            sidebar.classList.add('mobile-open');
            overlay.style.display = 'block';
        }
        function closeMobile() {
            sidebar.classList.remove('mobile-open');
            overlay.style.display = 'none';
        }
        if (hamburger) {
            hamburger.addEventListener('click', function () {
                sidebar.classList.contains('mobile-open') ? closeMobile() : openMobile();
            });
        }
        if (overlay) {
            overlay.addEventListener('click', closeMobile);
        }

        const sidebarToggle = document.getElementById('app-sidebar-toggle');
        const appMain = document.getElementById('main') || document.getElementById('app-main');
        const appTopbar = document.getElementById('app-topbar');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('close');
                if(appMain) appMain.classList.toggle('expand');
                if(appTopbar) appTopbar.classList.toggle('expand');
            });
        }

        const dashboardToggle = document.getElementById('dashboard-toggle');
        const dashboardAccordion = document.getElementById('dashboard-accordion');
        const dashboardChevron = document.getElementById('dashboard-chevron');
        if (dashboardToggle) {
            dashboardToggle.addEventListener('click', function () {
                dashboardAccordion.classList.toggle('open');
                dashboardChevron.classList.toggle('open');
            });
        }

        const otherToggle = document.getElementById('other-projects-toggle');
        const otherAccordion = document.getElementById('other-projects-accordion');
        const otherChevron = document.getElementById('other-projects-chevron');
        if (otherToggle) {
            otherToggle.addEventListener('click', function () {
                otherAccordion.classList.toggle('open');
                otherChevron.classList.toggle('open');
            });
        }

        const navLinks = sidebar.querySelectorAll('a.nav-link, a.nav-sub-link, a.nav-sub-link2');
        navLinks.forEach(function (link) {
            link.addEventListener('click', closeMobile);
        });
    });
</script>
