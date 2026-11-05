<?php
namespace App\Filament\Resources\Inventory\Pages;
use App\Filament\Resources\Inventory\InventoryItemResource;
use Filament\Resources\Pages\EditRecord;
class EditInventoryItem extends EditRecord{
    public function getSubheading(): ?string { return 'Perbarui informasi barang.'; }

 protected static string $resource=InventoryItemResource::class;
 
}
