<dl class="ces-item-details">
@foreach([
 'Barang'=>$movement->item?->name ?? 'Barang tidak tersedia',
 'Transaksi'=>['receive'=>'Stok masuk','consume'=>'BHP diambil','borrow'=>'Dipinjam','return'=>'Dikembalikan'][$movement->type] ?? $movement->type,
 'Qty'=>$movement->quantity.' '.$movement->item?->unit,
 'Stok setelah transaksi'=>$movement->balance_after.' '.$movement->item?->unit,
 'Pengguna / peminjam'=>$movement->person,
 'Pencatatan'=>$movement->checkout_id ? 'Mandiri / QR' : ($movement->user?->name ?? 'Pendataan'),
 'Rak saat pengambilan'=>$movement->location_snapshot ?: '—',
 'Keperluan / project'=>$movement->purpose ?: ($movement->notes ?: '—'),
 'Catatan'=>$movement->purpose ? ($movement->notes ?: '—') : '—',
 'Batas pengembalian'=>$movement->due_date?->translatedFormat('l, d M Y') ?? '—',
 'Waktu'=>$movement->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i'),
 'Qty belum kembali'=>$movement->type==='borrow' ? max(0,$movement->quantity-$movement->returns->sum('quantity')) : '—',
] as $label=>$value)<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach
</dl>
