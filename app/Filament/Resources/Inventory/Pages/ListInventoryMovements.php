<?php
namespace App\Filament\Resources\Inventory\Pages;
use App\Filament\Resources\Inventory\InventoryMovementResource;
use Filament\Resources\Pages\ListRecords;
class ListInventoryMovements extends ListRecords{
 protected static string $resource=InventoryMovementResource::class;
 protected static ?string $title='Rekap Barang';
 protected function getHeaderActions(): array {
  return [\App\Filament\Actions\InventoryUsageAction::make('borrow'), \App\Filament\Actions\InventoryUsageAction::make('consume')];
 }
 public function getSubheading(): ?string {return 'Pengambilan lewat QR langsung masuk di sini. Catat pengembalian setelah barang diterima.';}
}
