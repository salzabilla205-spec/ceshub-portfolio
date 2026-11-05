@php
    $mobileProjects = [
        ['Digital Twin Baterai', 'dashboard.digital-twin'],
        ['Digital Twin PV', 'dashboard.lab-scale'],
        ['HESS', 'riset.hess'],
        ['Monitoring Kumbung Jamur', 'dashboard.jamur'],
        ['Smart Water Pump', 'dashboard.living-lab'],
    ];
    $activeMobileProject = collect($mobileProjects)->first(fn ($item) => request()->routeIs($item[1]) || ($item[1] === 'riset.hess' && request()->routeIs('dashboard.hess')));
@endphp
<nav class="mobile-project-picker" aria-label="Pilih dashboard project">
    <span>Project</span>
    <details class="dropdown">
        <summary><span>{{ $activeMobileProject[0] ?? 'Pilih project' }}</span>@include('partials.ui-icon', ['icon'=>'down'])</summary>
        <div class="dropdown-menu">@foreach($mobileProjects as [$label,$route])<a href="{{ route($route) }}" @if($label === ($activeMobileProject[0] ?? '')) aria-current="page" @endif>{{ $label }}</a>@endforeach</div>
    </details>
</nav>
