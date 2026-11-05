<?php
namespace App\Filament\Actions;
use App\Models\InventoryItem;
use App\Services\InventoryService;
use Filament\Actions\Action;
use Filament\Forms\Components\{Select,Textarea,TextInput};
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Width;
class InventoryUsageAction
{
 public static function make(string $type): Action
 {
  $borrowing=$type==='borrow';
  return Action::make($type)->label($borrowing?'Pinjam barang':'Ambil BHP')
   ->icon($borrowing?'heroicon-o-plus':'heroicon-o-archive-box-arrow-down')->color('primary')
   ->modalHeading($borrowing?'Pinjam barang':'Ambil BHP')
   ->modalDescription($borrowing?'Catat barang yang dipinjam dan keperluannya.':'Catat BHP yang diambil dan keperluannya.')
   ->modalWidth(Width::Large)
   ->extraModalWindowAttributes(['class'=>'ces-usage-modal'])
   ->modalSubmitActionLabel($borrowing?'Catat peminjaman':'Catat pengambilan')
   ->schema([
    Select::make('item_id')->label('Pilih barang')->placeholder($borrowing?'Pilih barang pinjaman':'Pilih BHP')->required()->searchable()->columnSpanFull()
     ->options(fn()=>InventoryItem::where('is_active',true)->where('kind',$borrowing?'equipment':'consumable')->where('quantity','>',0)
      ->orderBy('location')->orderBy('name')->get()->mapWithKeys(fn($item)=>[$item->id=>$item->name.' · '.$item->quantity.' '.$item->unit.($item->location?' · '.$item->location:'')])->all()),
    Grid::make(['default'=>1,'sm'=>3])->schema([
     TextInput::make('quantity')->label('Qty')->numeric()->integer()->minValue(1)->required()->default(1),
     TextInput::make('person')->label($borrowing?'Nama Peminjam':'Nama Pengambil')->required()->maxLength(255)->columnSpan(['default'=>1,'sm'=>2]),
    ])->columnSpanFull(),
    Textarea::make('notes')->label('Keperluan')->rows(2)->required()->maxLength(2000)->columnSpanFull(),
   ])->action(function(array $data)use($type){
    app(InventoryService::class)->record(auth()->user(),(int)$data['item_id'],$type,(int)$data['quantity'],$data['person'],$data['notes']);
    Notification::make()->title('Transaksi tercatat')->body('Stok barang sudah diperbarui.')->success()->send();
   });
 }
}
