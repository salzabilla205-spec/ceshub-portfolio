@extends('inventory.public-layout')
@section('title', 'Rak '.$rack)
@section('content')
<section class="page-intro"><p class="eyebrow">PENGAMBILAN BARANG</p><h1>Rak {{ $rack }}</h1><p>Pilih jumlah barang yang diambil, lalu isi nama dan keperluan.</p></section>
@if($errors->any())<div class="form-error" role="alert"><strong>Belum tersimpan.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@if($items->isEmpty())
<section class="form-panel empty-state"><h2>Belum ada barang di rak ini.</h2><p>Lokasi barang belum diisi atau belum tercatat sebagai {{ $rack }}. Hubungi pengelola lab untuk memperbarui lokasi barang.</p><a href="{{ route('inventory.public') }}">Pilih rak lain</a></section>
@else
<form method="post" action="{{ route('inventory.public.store', $rack) }}" id="checkout-form">
@csrf<input type="hidden" name="token" value="{{ $token }}">
<section class="form-panel"><div class="section-top"><h2>1. Pilih barang</h2><span>{{ $items->count() }} jenis</span></div>
<label class="search-field" for="item-search">Cari barang<input type="search" id="item-search" placeholder="Nama atau kode barang" autocomplete="off"></label>
<div class="item-list">
@foreach($items as $item)
<div class="stock-item" data-kind="{{ $item->kind }}" data-search="{{ Str::lower($item->name.' '.$item->code) }}">
<div class="stock-info"><label for="qty-{{ $item->id }}">{{ $item->name }}</label><small>{{ $item->code }} · {{ $item->kind === 'equipment' ? 'Pinjam' : 'Habis pakai' }}</small><span class="stock-count {{ $item->quantity === 0 ? 'sold-out' : '' }}">{{ $item->stock_verified === false ? 'Jumlah belum dihitung' : ($item->quantity > 0 ? 'Tersedia '.$item->quantity.' '.$item->unit : 'Stok habis') }}</span></div>
<div class="stepper"><button type="button" data-step="-1" aria-label="Kurangi {{ $item->name }}" @disabled($item->quantity === 0 || $item->stock_verified === false)>−</button><input id="qty-{{ $item->id }}" name="quantities[{{ $item->id }}]" type="number" inputmode="numeric" step="1" min="0" max="{{ $item->quantity }}" value="{{ old('quantities.'.$item->id, 0) }}" aria-label="Jumlah {{ $item->name }}" @disabled($item->quantity === 0 || $item->stock_verified === false)><button type="button" data-step="1" aria-label="Tambah {{ $item->name }}" @disabled($item->quantity === 0 || $item->stock_verified === false)>+</button></div>
</div>
@endforeach
</div><p id="search-empty" hidden>Tidak ada barang yang cocok.</p>
</section>
<section class="form-panel"><h2>2. Isi pengambilan</h2>
<div class="field"><label for="person">Nama peminjam / pemakai</label><input id="person" name="person" autocomplete="name" required maxlength="255" placeholder="Nama kamu" value="{{ old('person') }}"></div>
<div class="field"><label for="purpose">Keperluan / project</label><input id="purpose" name="purpose" required maxlength="500" placeholder="Contoh: Praktikum rangkaian listrik" value="{{ old('purpose') }}"></div>
@if($items->contains('kind', 'equipment'))
<fieldset class="return-options" id="return-options"><legend>Rencana pengembalian</legend><p class="field-hint">Isi jika mengambil barang pinjaman.</p><div class="choice-grid">
@foreach($dates as $key => $choice)
<label class="date-choice"><input type="radio" name="return_choice" value="{{ $key }}" @checked(old('return_choice') === $key)><span><strong>{{ $choice['label'] }}</strong><small>{{ \Carbon\CarbonImmutable::parse($choice['date'])->translatedFormat('d M Y') }}</small></span></label>
@endforeach
</div><p id="return-date-hint" class="field-hint" aria-live="polite"></p></fieldset>
@endif
<div class="field"><label for="notes">Catatan <span class="optional">opsional</span></label><textarea id="notes" name="notes" rows="2" maxlength="2000" placeholder="Kalau ada yang perlu disampaikan">{{ old('notes') }}</textarea></div>
<p class="automatic-time">Waktu pengambilan tercatat otomatis saat disimpan.</p>
</section>
<div class="checkout-bar"><span id="selection-summary" aria-live="polite">Pilih barang terlebih dahulu</span><button class="primary-button" type="submit" id="submit-checkout">Simpan Pengambilan</button></div>
</form>
@endif
@endsection
@push('scripts')<script src="{{ asset('js/inventory-public.js') }}?v=20261022" defer></script>@endpush
