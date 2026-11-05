<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pembimbing dan anggota tim Control Energy System, Teknik Elektro Universitas Sebelas Maret.">
    <title>Tim | CES — Control Energy System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.landing-style')
    @include('tim.partials.style')
    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
    @include('tim.partials.compact-cards')
</head>
<body>
<a class="skip" href="#main">Lewati ke konten</a>
@include('partials.landing-header')
<main id="main" class="team-page wrap">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a> &nbsp;/&nbsp; Tim</nav>
    <div class="team-heading"><h1>Tim CES</h1><p>Pembimbing dan anggota riset Control Energy System, Teknik Elektro UNS.</p></div>
    @include('partials.team-filter', ['filterMembers'=>$anggotaPerAngkatan->flatten(1)])
    @if($supervisor)
        <section class="team-supervisor" aria-labelledby="supervisor-name" data-team-member="{{ $supervisor->id }}">
            @include('tim.partials.photo', ['person' => $supervisor])
            <div><div class="eyebrow">Dosen Pembimbing</div><h2 id="supervisor-name">{{ $supervisor->name }}</h2><p>{{ $supervisor->role }}</p>@include('tim.partials.contact', ['person' => $supervisor])</div>
        </section>
    @endif
    @if($anggotaPerAngkatan->count() > 1)
        <nav class="team-cohorts" aria-label="Pilih angkatan" data-team-cohorts>
            @foreach($anggotaPerAngkatan as $angkatan => $anggota)
                <a href="#angkatan-{{ $loop->index }}">{{ $angkatan !== '' ? 'Angkatan ' . $angkatan : 'Anggota Lainnya' }}</a>
            @endforeach
        </nav>
    @endif
    @forelse($anggotaPerAngkatan as $angkatan => $anggota)
        <section class="team-group" id="angkatan-{{ $loop->index }}" aria-labelledby="heading-{{ $loop->index }}" data-team-group>
            <div class="team-group-heading"><h2 id="heading-{{ $loop->index }}">{{ $angkatan !== '' ? 'Angkatan ' . $angkatan : 'Anggota Lainnya' }}</h2><span data-team-count>{{ $anggota->count() }} anggota</span></div>
            <div class="team-grid">
                @foreach($anggota as $member)
                    @include('tim.partials.member', ['member' => $member])
                @endforeach
            </div>
        </section>
    @empty
        <div class="empty">Data anggota tim belum tersedia.</div>
    @endforelse
</main>
@include('partials.landing-footer')
@include('partials.landing-script')
</body>
</html>
