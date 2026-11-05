<x-filament-panels::page>
<div class="ces-overview">
 <section class="ces-overview-summary" aria-label="Status barang lab">
  <div class="ces-loans"><p>Sedang dipinjam</p><strong>{{ $activeLoans }} <span>peminjaman</span></strong><a href="{{ \App\Filament\Resources\Inventory\InventoryMovementResource::getUrl() }}">Lihat peminjaman aktif →</a></div>
  @if($emptyConsumables->isNotEmpty())
  <div class="ces-stock-alert"><span>STOK BHP HABIS</span><ul>@foreach($emptyConsumables as $item)<li><strong>{{ $item->name }}</strong><small>Stok 0 {{ $item->unit }}{{ $item->location ? ' · '.$item->location : '' }}</small></li>@endforeach</ul><a href="{{ \App\Filament\Resources\Inventory\InventoryItemResource::getUrl() }}">Buka rekap barang →</a></div>
  @endif
 </section>
 <div class="ces-overview-toolbar"><div><h2>Barang lab</h2><p>Catat pengambilan, stok langsung diperbarui.</p></div><div class="ces-overview-buttons"><a class="ces-action-primary" href="{{ route('inventory.usage',['type'=>'borrow']) }}">＋ Pinjam barang</a><a href="{{ route('inventory.usage',['type'=>'consume']) }}">Ambil BHP</a></div></div>
 <div class="ces-overview-grid">
  <section class="ces-overview-panel ces-recent"><header><h2>Aktivitas terbaru</h2><a href="{{ \App\Filament\Resources\Inventory\InventoryMovementResource::getUrl() }}">Semua riwayat →</a></header><div class="ces-overview-table"><table><thead><tr><th>Barang / pemakai</th><th>Aktivitas</th><th>Qty</th><th>Waktu</th></tr></thead><tbody>
  @forelse($recentMovements as $movement)
   <tr><td><strong>{{ $movement->item?->name ?? 'Barang tidak tersedia' }}</strong><small>{{ $movement->person }}</small></td><td><span class="ces-status ces-status-{{ $movement->type }}">{{ ['borrow'=>'Dipinjam','consume'=>'BHP diambil','return'=>'Dikembalikan','receive'=>'Stok masuk'][$movement->type] ?? $movement->type }}</span></td><td>{{ $movement->quantity }} {{ $movement->item?->unit }}</td><td>{{ $movement->created_at->timezone('Asia/Jakarta')->format('H:i') }}<small>{{ $movement->created_at->timezone('Asia/Jakarta')->format('d/m/Y') }}</small></td></tr>
  @empty
   <tr><td colspan="4" class="ces-overview-empty">Belum ada aktivitas barang. Peminjaman dan pemakaian akan muncul di sini.</td></tr>
  @endforelse
  </tbody></table></div></section>
  <section class="ces-overview-panel ces-rekap"><header><h2>Kelola rekap</h2></header><p>Isi barang satu per satu atau masukkan Excel.</p><a class="ces-rekap-row" href="{{ \App\Filament\Resources\Inventory\InventoryItemResource::getUrl('create') }}"><strong>Tambah barang <span>＋</span></strong><small>Nama, lokasi, kondisi, stok</small></a><a class="ces-rekap-row" href="{{ \App\Filament\Resources\Inventory\InventoryItemResource::getUrl() }}"><strong>Import Excel / edit rekap <span>→</span></strong><small>Gunakan template CES</small></a></section>
 </div>
 <section class="ces-overview-panel ces-website"><header><h2>Kelola website</h2><span>Konten publik</span></header><div class="ces-website-grid">
 @foreach([
 ['Kabar riset','Berita, foto, dan kegiatan.',\App\Filament\Resources\Posts\PostResource::class,'Tulis kabar'],
 ['Project & monitoring','Informasi dan tim project.',\App\Filament\Resources\Projects\ProjectResource::class,'Tambah project'],
 ['Anggota & pembimbing','Profil dan keanggotaan.',\App\Filament\Resources\TeamMembers\TeamMemberResource::class,'Tambah anggota'],
 ] as [$title,$description,$resource,$action])
  <div><h3>{{ $title }}</h3><p>{{ $description }}</p><a href="{{ $resource::getUrl('create') }}">{{ $action }} ＋</a><a class="ces-edit-list" href="{{ $resource::getUrl() }}">Edit daftar</a></div>
 @endforeach
 </div></section>
 <footer class="ces-admin-footer"><span>© 2026 CES · Teknik Elektro, Universitas Sebelas Maret</span><span class="ces-footer-links"><a href="{{ route('home') }}">Lihat website ↗</a><span>CES v2.0</span></span></footer>
</div>
</x-filament-panels::page>
