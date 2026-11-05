<?php
namespace App\Filament\Resources\Inventory;
use App\Models\InventoryItem;
use App\Services\InventoryService;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle};
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\{Action,EditAction};
use Filament\Notifications\Notification;
class InventoryItemResource extends Resource {
 protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';
 protected static ?string $model=InventoryItem::class;
 protected static ?string $navigationLabel='Inventaris Barang';
 protected static ?string $modelLabel='Barang';
 protected static ?string $pluralModelLabel='Inventaris Barang';
 protected static ?int $navigationSort=5;
 public static function form(Schema $schema): Schema{return $schema->components([
  TextInput::make('code')->label('Kode barang')->required()->unique(ignoreRecord:true)->maxLength(100),
  TextInput::make('name')->label('Nama barang')->required()->maxLength(255),
  Select::make('kind')->label('Jenis')->options(['consumable'=>'Habis pakai','equipment'=>'Inventaris / bisa dipinjam'])->required()->disabledOn('edit'),
  TextInput::make('opening_quantity')->label('Qty awal')->helperText('Dicatat sebagai stok masuk awal.')->numeric()->integer()->minValue(0)->default(0)->visibleOn('create'),
  TextInput::make('unit')->label('Satuan')->default('pcs')->required()->maxLength(30),
  TextInput::make('location')->label('Lokasi / kode box')->placeholder('Box 1 · Rak A')->maxLength(255),
  Select::make('condition')->label('Kondisi')->options(['Baik'=>'Baik','Perlu diperiksa'=>'Perlu diperiksa','Rusak'=>'Rusak'])->default('Baik')->required(),
  Textarea::make('notes')->label('Catatan')->rows(2),Toggle::make('is_active')->label('Aktif')->default(true),
 ]);}
 public static function table(Table $table): Table{return $table->columns([
  TextColumn::make('name')->label('Barang')->searchable(['name','code'])->description(fn($record)=>$record->code)->wrap(),
  TextColumn::make('kind')->label('Jenis')->formatStateUsing(fn($state)=>$state==='consumable'?'Habis Pakai':'Inventaris')->badge()->color('primary'),
  TextColumn::make('quantity')->label('Qty tersedia')->formatStateUsing(fn($state,$record)=>$record->stock_verified===false?'Belum dihitung':$state.' '.$record->unit)->color(fn($state)=>$state===0?'danger':'gray')->sortable(),
  TextColumn::make('location')->label('Lokasi')->searchable()->wrap(),TextColumn::make('condition')->label('Kondisi')->toggleable(isToggledHiddenByDefault:true),
 ])->filters([
  \Filament\Tables\Filters\SelectFilter::make('kind')->label('Jenis barang')->options(['equipment'=>'Inventaris / bisa dipinjam','consumable'=>'Habis pakai']),
  \Filament\Tables\Filters\SelectFilter::make('condition')->label('Kondisi barang')->options(['Baik'=>'Baik','Perlu diperiksa'=>'Perlu diperiksa','Rusak'=>'Rusak']),
  \Filament\Tables\Filters\SelectFilter::make('is_active')->label('Status barang')->options([1=>'Aktif',0=>'Diarsipkan']),
  \Filament\Tables\Filters\SelectFilter::make('location')->label('Lokasi')->options(fn()=>InventoryItem::whereNotNull('location')->where('location','!=','')->orderBy('location')->pluck('location','location')->all()),
  \Filament\Tables\Filters\SelectFilter::make('stock')->label('Stok')->options(['empty'=>'Stok habis','available'=>'Stok tersedia'])->query(fn($query,array $data)=>($data['value']??null)==='empty'?$query->where('quantity',0):(($data['value']??null)==='available'?$query->where('quantity','>',0):$query)),
  \Filament\Tables\Filters\Filter::make('hide_empty_consumables')->label('Sembunyikan barang habis pakai yang stoknya habis')->default()->query(fn(\Illuminate\Database\Eloquent\Builder $query)=>$query->where(fn($items)=>$items->where('kind','equipment')->orWhere('quantity','>',0))),
 ])->recordActions([
  Action::make('countStock')->label('Isi jumlah fisik')->button()->size('sm')->color('primary')->visible(fn($record)=>$record->stock_verified===false)
   ->schema([TextInput::make('quantity')->label('Jumlah yang sudah dihitung')->numeric()->integer()->minValue(0)->required()])
   ->action(function($record,array $data){\Illuminate\Support\Facades\DB::transaction(function()use($record,$data){$item=InventoryItem::whereKey($record->id)->lockForUpdate()->firstOrFail();if($item->stock_verified)return;if((int)$data['quantity']>0){app(InventoryService::class)->record(auth()->user(),$item->id,'receive',(int)$data['quantity'],auth()->user()->name,'Penghitungan fisik awal');}else{$item->update(['stock_verified'=>true]);}});Notification::make()->title('Jumlah fisik tercatat')->success()->send();}),
  Action::make('receive')->label('Tambah stok')->icon('heroicon-o-plus')->button()->size('sm')->color('primary')->visible(fn($record)=>$record->is_active)
   ->schema([TextInput::make('quantity')->label('Qty masuk')->numeric()->integer()->minValue(1)->required(),TextInput::make('person')->label('Nama penanggung jawab')->required()->maxLength(255),Textarea::make('notes')->label('Catatan')->maxLength(2000)])
   ->action(function($record,array $data){app(InventoryService::class)->record(auth()->user(),$record->id,'receive',(int)$data['quantity'],$data['person'],$data['notes']??null);Notification::make()->title('Stok ditambahkan')->success()->send();}),
  EditAction::make()->label('Ubah')->button()->size('sm')->color('primary'),
  Action::make('details')->label('Detail')->button()->size('sm')->color('primary')->modalHeading(fn($record)=>$record->name)->modalContent(fn($record)=>view('filament.inventory-details',['item'=>$record->load(['lastLoan','lastReceipt'])]))->modalSubmitAction(false)->modalCancelActionLabel('Tutup'),
 ])->defaultSort(fn(\Illuminate\Database\Eloquent\Builder $query)=>$query->orderBy('location')->orderBy('name'));}
 public static function getPages(): array{return ['index'=>Pages\ListInventoryItems::route('/'),'create'=>Pages\CreateInventoryItem::route('/create'),'edit'=>Pages\EditInventoryItem::route('/{record}/edit')];}
 public static function getNavigationGroup(): ?string { return 'Barang lab'; }
}
