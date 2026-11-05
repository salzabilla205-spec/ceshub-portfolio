<!doctype html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Tim {{ $project->name }} — CES</title>@include('partials.landing-style')@include('tim.partials.style')    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
</head>
<body>@include('partials.landing-header')
<main class="team-page"><div class="wrap">
<p class="breadcrumb"><a href="{{ url('/') }}">Beranda</a> / <a href="{{ route('riset.show', $project->slug) }}">{{ $project->name }}</a> / Tim</p>
<header class="team-heading"><h1>Tim {{ $project->name }}</h1><p>Anggota dan pembimbing project.</p></header>
@include('partials.team-filter', ['filterMembers'=>$anggota])
@if($supervisor)<section class="team-supervisor" data-team-member="{{ $supervisor->id }}">@include('tim.partials.photo', ['person' => $supervisor])<div><span class="eyebrow">Dosen Pembimbing</span><h2>{{ $supervisor->name }}</h2><p>{{ $supervisor->role }}</p></div></section>@endif
<div class="team-grid">@foreach($anggota as $member)<article class="team-card" data-team-member="{{ $member->id }}">@include('tim.partials.photo', ['person' => $member])<h3>{{ $member->name }}</h3><p class="team-role">{{ $member->pivot->role_in_project ?: $member->role }}</p></article>@endforeach</div>
</div></main>@include('partials.landing-footer')@include('partials.landing-script')</body></html>
