<?php
namespace App\Filament\Resources\Inventory\Pages;
use App\Filament\Resources\Inventory\InventoryItemResource;
use Filament\Resources\Pages\CreateRecord;
class CreateInventoryItem extends CreateRecord{
    public function getSubheading(): ?string { return 'Tambahkan barang dan stok awal.'; }

 protected static string $resource=InventoryItemResource::class;
 protected static ?string $title='Tambah Barang';
 public function getBreadcrumb(): string { return 'Tambah'; }
 protected function getCreateFormAction(): \Filament\Actions\Action { return parent::getCreateFormAction()->label('Simpan barang'); }
 protected function getCreateAnotherFormAction(): \Filament\Actions\Action { return parent::getCreateAnotherFormAction()->label('Simpan & tambah lagi'); }
 
 protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model {
  return \Illuminate\Support\Facades\DB::transaction(function()use($data){
   $quantity=(int)($data['opening_quantity']??0);unset($data['opening_quantity']);
   $record=\App\Models\InventoryItem::create($data);
   if($quantity>0) app(\App\Services\InventoryService::class)->record(auth()->user(),$record->id,'receive',$quantity,auth()->user()->name,'Stok awal');
   return $record;
  });
 }
}
