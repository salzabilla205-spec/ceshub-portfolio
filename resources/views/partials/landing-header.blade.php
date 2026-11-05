@php
    $projectLinks = [
        ['Digital Twin Baterai', 'Sistem AC, DC, dan BMS Daly', route('dashboard.digital-twin')],
        ['Digital Twin PV', 'Daya, tegangan, arus, cahaya, dan suhu', route('dashboard.lab-scale')],
        ['HESS', 'Baterai utama, superkapasitor, dan kendaraan', route('dashboard.hess')],
        ['Monitoring Kumbung Jamur', 'Suhu, kelembapan, dan kondisi kumbung', route('dashboard.jamur')],
        ['Smart Water Pump', 'Debit air dan kelistrikan pompa', route('dashboard.living-lab')],
    ];
@endphp
<header class="header {{ request()->routeIs('home') ? '' : 'is-scrolled' }}"><div class="wrap nav">
        <a class="brand" href="{{ route('home') }}" aria-label="CES Beranda"><img src="{{ asset('assets/logo/ces-logo.png') }}" alt="" width="56" height="56"><span><strong>CES</strong><small>Control Energy System</small></span></a>
        <nav class="desktop-nav" aria-label="Navigasi utama">
            <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a><a href="{{ route('riset.index') }}" @if(request()->routeIs('riset.index')) aria-current="page" @endif>Kabar Riset</a><a href="{{ route('tim.index') }}" @if(request()->routeIs('tim.index')) aria-current="page" @endif>Tim</a>
            <details class="dropdown"><summary>Project <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></summary><div class="dropdown-menu">@foreach ($projectLinks as [$label, $description, $link])<a href="{{ $link }}">{{ $label }} <span aria-hidden="true">↗</span></a>@endforeach<a href="{{ route('home') }}#project">Semua Project <span aria-hidden="true">↓</span></a></div></details>
        </nav>
        <div class="header-actions">
            <details class="header-theme"><summary aria-label="Buka pilihan tema">@include('partials.ui-icon', ['icon'=>'sun'])</summary><div class="header-theme-menu">@include('partials.theme-picker')</div></details>
            <details class="dropdown mobile-nav"><summary aria-label="Buka navigasi">@include('partials.ui-icon', ['icon'=>'menu'])</summary><nav class="dropdown-menu" aria-label="Navigasi seluler"><a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a><a href="{{ route('riset.index') }}" @if(request()->routeIs('riset.index')) aria-current="page" @endif>Kabar Riset</a><a href="{{ route('tim.index') }}" @if(request()->routeIs('tim.index')) aria-current="page" @endif>Tim</a><span class="mobile-project-label">Project</span>@foreach ($projectLinks as [$label, $description, $link])
                @php($menuCategory = match($label) { 'Monitoring Kumbung Jamur'=>'agriculture', 'Smart Water Pump'=>'water', default=>'energy' })
                <a href="{{ $link }}" class="mobile-project-link"><span class="project-category-dot" data-category="{{ $menuCategory }}" aria-hidden="true"></span>{{ $label }}</a>
            @endforeach</nav></details>
        </div>

    </div></header>


