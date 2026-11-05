<?php
namespace App\Filament\Resources\Inventory\Pages;
use App\Filament\Resources\Inventory\InventoryItemResource;
use App\Services\InventoryImportService;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\{Action,CreateAction};
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
class ListInventoryItems extends ListRecords
{
    public function getSubheading(): ?string { return 'Kelola barang, stok, dan lokasi.'; }

    protected static string $resource = InventoryItemResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Action::make('rackQr')->label('QR Rak')->url(route('inventory.public'))->openUrlInNewTab()->color('gray'),
            CreateAction::make()->label('Tambah barang')->icon('heroicon-o-plus'),
            Action::make('import')->label('Masukkan Excel')->icon('heroicon-o-document-arrow-up')->color('primary')->modalHeading('Masukkan rekap barang dari Excel')
                ->modalDescription('Gunakan template. Semua baris diperiksa sebelum disimpan. Kode barang yang sudah ada tidak ditimpa; perubahan stok memakai transaksi.')
                ->schema([FileUpload::make('file')->label('File template yang sudah diisi (.xlsx)')->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->maxSize(5120)->storeFiles(false)->required()])
                ->action(function (array $data) {
                    $service = app(InventoryImportService::class);
                    $rows = $service->read($data['file']->getRealPath());
                    $count = $service->import(auth()->user(), $rows);
                    Notification::make()->title($count.' barang berhasil dimasukkan')->success()->send();
                }),
        ];
    }
}
