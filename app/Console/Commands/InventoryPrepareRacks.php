<?php

namespace App\Console\Commands;

use App\Services\InventoryRackDraftService;
use Illuminate\Console\Command;

class InventoryPrepareRacks extends Command
{
    protected $signature = 'inventory:prepare-racks {--apply : Simpan barang baru tanpa menimpa barang lama}';
    protected $description = 'Daftar A1 dan A2 dari pemilik; solder rusak, alat belum dikenali, dan A3 tidak dimasukkan';

    public function handle(InventoryRackDraftService $service): int
    {
        if (!$this->option('apply')) {
            $this->table(['Kode','Barang','Jumlah'], array_map(fn($r)=>[$r['code'],$r['name'],$r['quantity'] ?? 'Belum dihitung'], InventoryRackDraftService::ITEMS));
            $this->line('Pratinjau saja. Gunakan --apply untuk menyimpan. Tidak mengubah barang yang sudah ada.');
            return self::SUCCESS;
        }
        $this->table(['Kode','Hasil'], array_map(fn($r)=>[$r['code'],$r['status']],$service->register()));
        return self::SUCCESS;
    }
}
