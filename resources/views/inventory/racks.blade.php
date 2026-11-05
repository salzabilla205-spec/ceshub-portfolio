@extends('inventory.public-layout')
@section('title', 'Pilih Rak')
@section('content')
<section class="page-intro"><p class="eyebrow">PENGAMBILAN BARANG</p><h1>Ambil dari rak mana?</h1><p>Scan QR di rak atau pilih lokasi di bawah. Isi sekali untuk beberapa barang.</p></section>
<div class="rack-grid">@foreach($racks as $rack)<a class="rack-link" href="{{ route('inventory.public.rack', $rack) }}"><span>RAK</span><strong>{{ $rack }}</strong><span aria-hidden="true">↗</span></a>@endforeach</div>
<p class="print-link"><a href="{{ route('inventory.public.labels') }}">Unduh label QR untuk dicetak</a></p>
@endsection
