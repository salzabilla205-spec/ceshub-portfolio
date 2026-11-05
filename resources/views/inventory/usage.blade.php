<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $type === 'borrow' ? 'Pinjam barang' : 'Ambil BHP' }} | CES Admin</title>@include('partials.landing-style')<link rel="stylesheet" href="{{ asset('css/inventory-usage.css') }}"><script defer src="{{ asset('js/inventory-usage.js') }}"></script>    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}?v={{ filemtime(public_path('css/site-responsive.css')) }}">
</head>
<body>@include('partials.landing-header')<main class="wrap inventory-usage">
<nav class="inventory-breadcrumb"><a href="{{ url('/admin') }}">Admin CES</a> / Pemakaian barang</nav>
<header><span class="inventory-eyebrow">BARANG LAB PAK HARI</span><h1>{{ $type === 'borrow' ? 'Pinjam barang inventaris' : 'Ambil barang habis pakai' }}</h1><p>Isi nama dan keperluan, lalu catat transaksi. Tidak ada tahap persetujuan. Stok tersedia langsung berkurang setelah berhasil disimpan.</p></header>
<nav class="inventory-tabs" aria-label="Jenis pemakaian"><a href="{{ route('inventory.usage', ['type'=>'borrow']) }}" @if($type==='borrow') aria-current="page" @endif>Pinjam barang</a><a href="{{ route('inventory.usage', ['type'=>'consume']) }}" @if($type==='consume') aria-current="page" @endif>Ambil BHP</a></nav>
@if(session('inventory_success'))<div class="inventory-success" role="status">{{ session('inventory_success') }}</div>@endif
@if($errors->any())<div class="inventory-error" role="alert"><strong>Transaksi belum disimpan.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="inventory-use-grid"><section class="inventory-use-card"><h2>Catat {{ $type === 'borrow' ? 'peminjaman' : 'pengambilan' }}</h2>
<form method="post" action="{{ route('inventory.usage.store') }}" data-inventory-use>@csrf<input type="hidden" name="type" value="{{ $type }}">
<label>Pilih barang<select name="item_id" required data-inventory-item><option value="">Pilih barang</option>@foreach($items as $item)<option value="{{ $item->id }}" data-stock="{{ $item->quantity }}" data-unit="{{ $item->unit }}" data-location="{{ $item->location }}" @selected((string)old('item_id')===(string)$item->id) @disabled($item->quantity===0)>{{ $item->name }} · {{ $item->quantity }} {{ $item->unit }} tersedia{{ $item->location ? ' · '.$item->location : '' }}</option>@endforeach</select></label>
<p class="inventory-stock-hint" data-stock-hint>Daftar hanya menampilkan barang dari rekap inventaris. Pilih namanya untuk melihat stok dan lokasi penyimpanan.</p>
<label>Qty<input type="number" name="quantity" value="{{ old('quantity',1) }}" min="1" max="1000000" step="1" required data-inventory-quantity></label>
<label>{{ $type === 'borrow' ? 'Nama Peminjam' : 'Nama Pengambil' }}<input type="text" name="person" value="{{ old('person') }}" maxlength="255" autocomplete="name" required></label>
<label>Keperluan<textarea name="notes" rows="3" maxlength="2000" required placeholder="Misalnya untuk project atau kegiatan praktikum">{{ old('notes') }}</textarea></label>
<button type="submit" @disabled(!$items->contains(fn($item)=>$item->quantity>0))>{{ $type==='borrow' ? 'Catat peminjaman' : 'Catat pengambilan BHP' }}</button><small>Pastikan barang dan jumlah benar. Simpan dahulu sebelum membawa barang dari tempatnya.</small>
</form></section>
<aside class="inventory-use-card"><h2>Setelah dicatat</h2>@if($type==='borrow')<p>Inventaris tetap terdaftar. Stok tersedia berkurang selama dipinjam.</p><p>Untuk mengembalikan, buka riwayat, cari nama peminjam dan transaksi barangnya, lalu pilih <strong>Kembalikan barang</strong>.</p>@else<p>Barang habis pakai mengurangi stok permanen. Jika stok habis, barang disembunyikan dari daftar standar, tetapi riwayat pemakaian tetap ada.</p>@endif
<a href="{{ \App\Filament\Resources\Inventory\InventoryMovementResource::getUrl() }}">Lihat riwayat / kembalikan barang →</a><a href="{{ \App\Filament\Resources\Inventory\InventoryItemResource::getUrl() }}">Lihat stok dan rekap barang →</a><a href="{{ url('/admin') }}">Kembali ke beranda admin →</a>
@if($items->isEmpty())<p class="inventory-empty">Belum ada barang jenis ini. Pengelola perlu mengisi rekap atau import Excel terlebih dahulu.</p>@endif</aside></div>
</main>@include('partials.landing-footer')</body></html>
