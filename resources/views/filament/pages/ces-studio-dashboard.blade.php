<x-filament-panels::page>
@php
 $inventory = \App\Filament\Resources\Inventory\InventoryItemResource::class;
 $history = \App\Filament\Resources\Inventory\InventoryMovementResource::class;
 $projects = \App\Filament\Resources\Projects\ProjectResource::class;
 $emptyStockUrl = $inventory::getUrl('index', ['filters' => ['is_active' => ['value' => '1'], 'kind' => ['value' => 'consumable'], 'stock' => ['value' => 'empty'], 'hide_empty_consumables' => ['isActive' => false]]]);
@endphp
<div class="ces-studio-overview">
 <section class="ces-studio-stats" aria-label="Ringkasan CES">
 @foreach([
  ['Jumlah Jenis Barang', $itemCount, 'Item dalam rekap', $inventory::getUrl('index', ['filters'=>['is_active'=>['value'=>'1'],'hide_empty_consumables'=>['isActive'=>false]]]), 'normal'],
  ['Peminjaman aktif', $activeLoans, 'Belum seluruhnya kembali', $history::getUrl('index', ['filters' => ['status' => ['value' => 'borrow']]]), 'warning'],
  ['BHP habis', $emptyConsumables->count(), 'Perlu diisi kembali', $emptyStockUrl, 'danger'],
  ['Project terbit', $publishedProjects, 'Project dipublikasikan', $projects::getUrl('index', ['filters' => ['status' => ['value' => 'published'], 'content_type' => ['value' => 'project']]]), 'normal'],
 ] as [$label, $value, $description, $url, $tone])
  <a class="ces-studio-stat ces-tone-{{ $tone }}" href="{{ $url }}" aria-label="{{ $label }}: {{ $value }}, buka daftar">
   <span class="ces-studio-stat-label">{{ $label }}</span><span class="ces-studio-stat-value"><strong>{{ $value }}</strong><small>{{ $description }}</small></span>
  </a>
 @endforeach
 </section>
 <div class="ces-studio-columns">
  <section class="ces-studio-panel"><header><h2>Aktivitas terbaru</h2><a href="{{ $history::getUrl() }}">Semua riwayat →</a></header>
  @forelse($recentMovements as $movement)
   @php $tone = $movement->type === 'borrow' ? 'warning' : 'normal'; $verb = ['borrow'=>'meminjam', 'consume'=>'mengambil', 'return'=>'mengembalikan', 'receive'=>'menambahkan stok'][$movement->type] ?? ''; @endphp
   <div class="ces-studio-row ces-tone-{{ $tone }}"><div><p>{{ $movement->person }} {{ $verb }} {{ $movement->item?->name ?? 'barang' }}</p><small>{{ $movement->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} · Qty {{ $movement->quantity }} {{ $movement->item?->unit }}</small></div><span class="ces-studio-status">{{ ['borrow'=>'Dipinjam','consume'=>'BHP diambil','return'=>'Dikembalikan','receive'=>'Stok masuk'][$movement->type] ?? $movement->type }}</span></div>
  @empty
   <div class="ces-studio-empty"><x-filament::icon icon="heroicon-o-clipboard-document-list" /><p>Belum ada catatan terbaru</p></div>
  @endforelse
  </section>
  <section class="ces-studio-panel"><header><h2>Stok BHP</h2><a href="{{ $inventory::getUrl('index', ['filters'=>['is_active'=>['value'=>'1'],'kind'=>['value'=>'consumable'],'hide_empty_consumables'=>['isActive'=>false]]]) }}">Inventaris barang →</a></header>
  @forelse($stockItems as $item)
   @php $received = (int) $item->received_quantity; $ratio = $received > 0 ? min(100, (int) round($item->quantity / $received * 100)) : 100; $tone = $item->quantity === 0 ? 'danger' : ($ratio <= 20 ? 'warning' : 'normal'); @endphp
   <div class="ces-studio-row ces-tone-{{ $tone }}"><div><p>{{ $item->name }}</p><div class="ces-studio-stock-qty"><small>Qty {{ $item->quantity }} {{ $item->unit }}</small><span class="ces-studio-stock-track" role="meter" aria-label="Stok {{ $item->name }} dibanding total stok masuk" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $ratio }}"><i style="width:{{ $item->quantity === 0 ? 0 : $ratio }}%"></i></span></div></div><span class="ces-studio-status">{{ $item->quantity === 0 ? 'Habis' : ($ratio <= 20 ? 'Menipis' : 'Tersedia') }}</span></div>
  @empty
   <div class="ces-studio-empty"><x-filament::icon icon="heroicon-o-archive-box" /><p>Belum ada barang habis pakai.</p><a href="{{ $inventory::getUrl('create') }}">Tambah barang →</a></div>
  @endforelse
  </section>
 </div>
</div>
</x-filament-panels::page>
