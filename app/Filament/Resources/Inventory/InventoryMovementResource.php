<?php
namespace App\Filament\Resources\Inventory;
use App\Models\InventoryMovement;
use App\Services\InventoryService;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\{TextInput,Textarea};
use Filament\Actions\Action;
use Filament\Notifications\Notification;
class InventoryMovementResource extends Resource {
 protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
 protected static ?string $model=InventoryMovement::class;
 protected static ?string $navigationLabel='Rekap Barang';
 protected static ?string $pluralModelLabel='Rekap Barang';
 protected static ?int $navigationSort=6;
 public static function canCreate(): bool{return false;}
 public static function table(Table $table): Table{return $table->columns([
  TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y, H:i')->sortable(),
  TextColumn::make('person')->label('Peminjam / pengguna')->searchable()->wrap(),
  TextColumn::make('location_snapshot')->label('Rak')->placeholder('—')->toggleable(),
  TextColumn::make('item.name')->label('Barang')->searchable()->wrap(),
  TextColumn::make('quantity')->label('Qty')->formatStateUsing(fn($state,$record)=>$state.' '.$record->item?->unit),
  TextColumn::make('type')->label('Status')->formatStateUsing(fn($state)=>['receive'=>'Stok masuk','consume'=>'BHP diambil','borrow'=>'Dipinjam','return'=>'Dikembalikan'][$state])->badge()->color(fn($state)=>$state==='borrow'?'warning':'primary'),
  TextColumn::make('balance_after')->label('Stok setelah transaksi')->toggleable(isToggledHiddenByDefault:true),
  TextColumn::make('recorder')->label('Pencatat')->state(fn($record)=>$record->checkout_id?'Mandiri / QR':($record->user?->name ?? 'Pendataan'))->toggleable(isToggledHiddenByDefault:true),
  TextColumn::make('purpose')->label('Keperluan / project')->state(fn($record)=>$record->purpose ?: $record->notes)->wrap()->toggleable(),
  TextColumn::make('due_date')->label('Batas kembali')->date('d M Y')->placeholder('—')->color(fn($record)=>$record->type==='borrow'&&$record->due_date?->lt(today())&&$record->quantity>$record->returns()->sum('quantity')?'danger':'gray')->sortable(),
  TextColumn::make('notes')->label('Catatan')->wrap()->toggleable(isToggledHiddenByDefault:true),
  TextColumn::make('remaining')->label('Qty belum kembali')->state(fn($record)=>$record->type==='borrow'?$record->quantity-$record->returns()->sum('quantity'):null)->placeholder('—')->toggleable(isToggledHiddenByDefault:true),
  ])->deferFilters(false)->filters([
   \Filament\Tables\Filters\SelectFilter::make('status')->label('Status transaksi')->placeholder('Semua')
    ->options(['borrow'=>'Dipinjam','return'=>'Dikembalikan','consume'=>'BHP diambil','receive'=>'Stok masuk'])
    ->query(function(\Illuminate\Database\Eloquent\Builder $query,array $data){
     $type=$data['value']??null;
     if(!in_array($type,['borrow','return','consume','receive'],true))return $query;
     $query->where('type',$type);
     if($type==='borrow')$query->whereRaw("quantity > (select coalesce(sum(r.quantity), 0) from inventory_movements as r where r.loan_id = inventory_movements.id and r.type = ?)",['return']);
     return $query;
    }),
   \Filament\Tables\Filters\SelectFilter::make('origin')->label('Pencatatan')->options(['qr'=>'Mandiri / QR','admin'=>'Admin'])
    ->query(fn($query,array $data)=>match($data['value']??null){'qr'=>$query->whereNotNull('checkout_id'),'admin'=>$query->whereNull('checkout_id'),default=>$query}),
   \Filament\Tables\Filters\SelectFilter::make('rack')->label('Rak QR')->options(fn()=>array_combine(config('inventory.racks'),config('inventory.racks')))
    ->query(fn($query,array $data)=>in_array($data['value']??null,config('inventory.racks'),true)?$query->whereHas('checkout',fn($q)=>$q->where('rack',$data['value'])):$query),
  ])->recordActions([
  Action::make('return')->label('Kembalikan barang')->button()->size('sm')->color('primary')->visible(fn($record)=>$record->type==='borrow'&&$record->quantity>$record->returns()->sum('quantity'))
  ->schema([TextInput::make('quantity')->label('Qty dikembalikan')->numeric()->integer()->minValue(1)->required(),Textarea::make('notes')->label('Catatan kondisi')->maxLength(2000)])
  ->action(function($record,array $data){app(InventoryService::class)->record(auth()->user(),$record->inventory_item_id,'return',(int)$data['quantity'],$record->person,$data['notes']??null,$record->id);Notification::make()->title('Pengembalian tercatat')->success()->send();}),
  Action::make('details')->label('Detail')->button()->size('sm')->color('primary')->modalHeading('Detail transaksi')->modalContent(fn($record)=>view('filament.movement-details',['movement'=>$record->load(['item','user','returns'])]))->modalSubmitAction(false)->modalCancelActionLabel('Tutup'),
 ])->defaultSort('id','desc');}
 public static function getPages(): array{return ['index'=>Pages\ListInventoryMovements::route('/')];}
 public static function getNavigationGroup(): ?string { return 'Barang lab'; }
}
