@extends('inventory.public-layout')
@section('title', 'Bukti Pengambilan')
@section('content')
<section class="page-intro"><p class="eyebrow">PENGAMBILAN TERCATAT</p><h1>Sudah tersimpan.</h1><p>Stok dan rekap barang sudah diperbarui. Simpan bukti ini dengan screenshot atau cetak.</p></section>
<section class="form-panel receipt"><div class="section-top"><h2>Rak {{ $checkout->rack }}</h2><span>{{ $checkout->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</span></div>
<dl class="receipt-meta"><div><dt>Nama</dt><dd>{{ $checkout->person }}</dd></div><div><dt>Keperluan / project</dt><dd>{{ $checkout->purpose }}</dd></div></dl>
@foreach($checkout->movements as $movement)
<div class="receipt-item"><div><strong>{{ $movement->item?->name }}</strong><small>{{ $movement->type === 'borrow' ? 'Dipinjam' : 'Habis pakai' }}@if($movement->due_date) · Kembali paling lambat {{ $movement->due_date->translatedFormat('l, d M Y') }}@endif</small></div><strong>{{ $movement->quantity }} {{ $movement->item?->unit }}</strong></div>
@endforeach
@if($checkout->notes)<div class="receipt-note"><span>Catatan</span><p>{{ $checkout->notes }}</p></div>@endif
<p class="receipt-number">Bukti {{ strtoupper($checkout->id) }}</p>
</section>
<div class="receipt-actions"><a class="primary-button" href="{{ route('inventory.public.rack', $checkout->rack) }}">Ambil barang lagi</a><button type="button" class="secondary-button" id="print-receipt">Cetak bukti</button></div>
@endsection
@push('scripts')<script src="{{ asset('js/inventory-public.js') }}?v=20261022" defer></script>@endpush
