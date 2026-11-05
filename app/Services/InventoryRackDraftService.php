<?php

namespace App\Services;

use App\Models\InventoryItem;
use Illuminate\Support\Facades\DB;

class InventoryRackDraftService
{
    public const ITEMS = [
        ['code'=>'A1-ATR','name'=>'Atraktor / penyedot timah','quantity'=>null,'note'=>'Jumlah belum dihitung.'],
        ['code'=>'A1-JMM','name'=>'Kabel jumper M-M','quantity'=>null,'note'=>'Tinggal sedikit; jumlah belum dihitung.'],
        ['code'=>'A1-JMF','name'=>'Kabel jumper M-F','quantity'=>null,'note'=>'Banyak; jumlah belum dihitung.'],
        ['code'=>'A1-JFF','name'=>'Kabel jumper F-F','quantity'=>null,'note'=>'Tinggal sedikit; jumlah belum dihitung.'],
        ['code'=>'A1-USBM','name'=>'Kabel USB Micro-B','quantity'=>null,'note'=>'Jumlah belum dihitung.'],
        ['code'=>'A1-USBB','name'=>'Kabel USB Type-B (Arduino Uno)','quantity'=>null,'note'=>'Jumlah belum dihitung.'],
        ['code'=>'A1-CRIMP','name'=>'Tang crimping','quantity'=>null,'note'=>'Jumlah belum dihitung.'],
        ['code'=>'A1-TANG','name'=>'Tang','quantity'=>null,'note'=>'Jenis dan jumlah belum diperinci.'],
        ['code'=>'A1-POTONG','name'=>'Tang potong','quantity'=>null,'note'=>'Jumlah belum dihitung.'],
        ['code'=>'A1-CLAMP','name'=>'Clamp meter','quantity'=>null,'note'=>'Jumlah belum dihitung.'],
        ['code'=>'A1-WATT','name'=>'Watt meter','quantity'=>null,'note'=>'Jumlah belum dihitung.'],
        ['code'=>'A1-SUHU','name'=>'Alat ukur suhu','quantity'=>null,'note'=>'Jumlah belum dihitung.'],
        ['code'=>'A1-OBENG','name'=>'Obeng','quantity'=>3,'note'=>'Tiga buah sesuai keterangan pemilik pada 22 Oktober 2026.'],
        ['code'=>'A1-SETSOLDER','name'=>'Set solder','quantity'=>null,'note'=>'Set tambahan menurut pemilik. Solder rusak yang disebut sebelumnya tidak dimasukkan.'],
        ['code'=>'A1-SETOBENG','name'=>'Set obeng','quantity'=>null,'note'=>'Terpisah dari tiga obeng satuan. Jumlah set belum dihitung.'],
        ['code'=>'A2-PCB','name'=>'Penjepit PCB','quantity'=>null,'note'=>'Jumlah belum dihitung.'],
        ['code'=>'A2-PEMBERSIH','name'=>'Pembersih mata solder','quantity'=>null,'note'=>'Jenis pembersih belum diperinci; jumlah belum dihitung.'],
        ['code'=>'A2-SKUN','name'=>'Wadah skun','quantity'=>2,'unit'=>'wadah','note'=>'Dua wadah. Jumlah skun di dalamnya belum dihitung.'],
        ['code'=>'A2-HEATSHRINK','name'=>'Wadah heatshrink','quantity'=>1,'unit'=>'wadah','note'=>'Satu wadah. Jumlah heatshrink di dalamnya belum dihitung.'],
        ['code'=>'A2-BAUT','name'=>'Wadah baut dan mur','quantity'=>1,'unit'=>'wadah','note'=>'Satu wadah. Jumlah baut dan mur di dalamnya belum dihitung.'],
        ['code'=>'A2-TENOL','name'=>'Tenol / timah solder','quantity'=>9,'kind'=>'consumable','unit'=>'unit','note'=>'Sembilan unit sesuai pemilik; ukuran kemasan belum diperinci.'],
        ['code'=>'A2-18AWG','name'=>'Kabel 18 AWG','quantity'=>null,'note'=>'Panjang dan jumlah belum dihitung.'],
        ['code'=>'A2-25AWG','name'=>'Kabel 25 AWG','quantity'=>null,'note'=>'Panjang dan jumlah belum dihitung.'],
        ['code'=>'A2-RIBBON','name'=>'Kabel pelangi / ribbon','quantity'=>null,'note'=>'Panjang dan jumlah belum dihitung.'],
        ['code'=>'A2-PHF','name'=>'Pin header female','quantity'=>2,'kind'=>'consumable','unit'=>'unit','note'=>'Dua unit; jumlah pin per unit belum diperinci.'],
        ['code'=>'A2-PHM','name'=>'Pin header male','quantity'=>11,'kind'=>'consumable','unit'=>'unit','note'=>'Sebelas unit; jumlah pin per unit belum diperinci.'],
        ['code'=>'A2-PHL','name'=>'Pin header L','quantity'=>1,'kind'=>'consumable','unit'=>'unit','note'=>'Satu unit; jumlah pin belum diperinci.'],
        ['code'=>'A2-PINSET','name'=>'Pinset','quantity'=>1,'note'=>'Satu buah sesuai keterangan pemilik.'],
    ];

    public function register(): array
    {
        return DB::transaction(function () {
            $result = [];
            foreach (self::ITEMS as $row) {
                $existing = InventoryItem::where('code', $row['code'])->orWhere('name', $row['name'])->first();
                if ($existing) {
                    $result[] = ['code'=>$row['code'], 'status'=>'skipped_existing', 'existing_id'=>$existing->id];
                    continue;
                }
                $rack = explode('-', $row['code'], 2)[0];
                $item = InventoryItem::create([
                    'code'=>$row['code'], 'name'=>$row['name'], 'kind'=>$row['kind'] ?? 'equipment', 'unit'=>$row['unit'] ?? 'pcs',
                    'location'=>$rack, 'condition'=>'Perlu diperiksa', 'notes'=>$row['note'],
                    'is_active'=>true, 'stock_verified'=>$row['quantity'] !== null,
                ]);
                if ($row['quantity'] !== null) {
                    $item->forceFill(['quantity'=>$row['quantity']])->save();
                    $item->movements()->create([
                        'user_id'=>null, 'type'=>'receive', 'quantity'=>$row['quantity'], 'balance_after'=>$row['quantity'],
                        'person'=>'Pendataan '.$rack, 'purpose'=>'Stok awal berdasarkan keterangan pemilik, 22 Oktober 2026',
                        'notes'=>$row['note'], 'location_snapshot'=>$rack,
                    ]);
                }
                $result[] = ['code'=>$row['code'], 'status'=>$row['quantity'] === null ? 'created_uncounted' : 'created_counted'];
            }
            return $result;
        });
    }
}
