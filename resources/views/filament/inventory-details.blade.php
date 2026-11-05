<dl class="ces-item-details">
@foreach([
 'Kode barang'=>$item->code,
 'Jenis'=>$item->kind==='consumable'?'Habis Pakai':'Inventaris',
 'Qty tersedia'=>$item->quantity.' '.$item->unit,
 'Lokasi'=>$item->location ?: 'Belum diisi',
 'Kondisi'=>$item->condition,
 'Terakhir dipinjam oleh'=>$item->lastLoan?->person ?? 'Belum pernah dipinjam',
 'Ditambahkan'=>$item->created_at?->timezone('Asia/Jakarta')->format('d/m/Y H:i'),
 'Input stok terakhir'=>$item->lastReceipt?->created_at?->timezone('Asia/Jakarta')->format('d/m/Y H:i') ?? 'Belum ada stok masuk',
 'Status'=>$item->is_active?'Aktif':'Diarsipkan',
 'Catatan'=>$item->notes ?: 'Tidak ada catatan',
] as $label=>$value)<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach
</dl>
