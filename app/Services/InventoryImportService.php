<?php
namespace App\Services;

use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use ZipArchive;

class InventoryImportService
{
    public const HEADERS = ['kode', 'nama_barang', 'jenis', 'jumlah', 'satuan', 'lokasi', 'kondisi', 'catatan'];

    public function read(string $file): array
    {
        $fail = fn ($message) => throw ValidationException::withMessages(['file' => $message]);
        if (!is_file($file) || filesize($file) > 5 * 1024 * 1024) $fail('File maksimal 5 MB.');
        $zip = new ZipArchive;
        if ($zip->open($file) !== true) $fail('Gunakan file Excel .xlsx dari template yang disediakan.');
        try {
            if ($zip->numFiles > 100) $fail('Struktur file terlalu besar. Gunakan template.');
            $size = 0;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $size += $zip->statIndex($i)['size'];
                if ($size > 20 * 1024 * 1024) $fail('Ukuran isi file terlalu besar.');
            }
            $xml = function (string $entry) use ($zip, $fail) {
                $content = $zip->getFromName($entry);
                if ($content === false) return null;
                if (stripos($content, '<!DOCTYPE') !== false || stripos($content, '<!ENTITY') !== false) $fail('XML dengan entitas tidak didukung.');
                $previous = libxml_use_internal_errors(true);
                try { $document = simplexml_load_string($content, \SimpleXMLElement::class, LIBXML_NONET); }
                finally { libxml_clear_errors(); libxml_use_internal_errors($previous); }
                if ($document === false) $fail('Format Excel tidak dapat dibaca.');
                return $document;
            };
            $strings = [];
            $shared = $xml('xl/sharedStrings.xml');
            if ($shared !== null) foreach ($shared->xpath('//*[local-name()="si"]') as $string) {
                $strings[] = implode('', array_map('strval', $string->xpath('.//*[local-name()="t"]')));
            }
            $sheet = $xml('xl/worksheets/sheet1.xml');
            if ($sheet === null) $fail('Sheet pertama harus Data Barang dari template.');
            $rows = []; $headerFound = false;
            foreach ($sheet->xpath('//*[local-name()="sheetData"]/*[local-name()="row"]') as $row) {
                $number = (int) $row['r'];
                if ($number > 1001) $fail('Maksimal 1.000 baris data per import.');
                $values = array_fill(0, 8, '');
                foreach ($row->xpath('./*[local-name()="c"]') as $cell) {
                    if ($cell->xpath('./*[local-name()="f"]')) $fail('Baris '.$number.': formula tidak didukung. Isi nilai langsung.');
                    preg_match('/^([A-Z]+)[0-9]+$/', (string) $cell['r'], $match);
                    $column = 0;
                    foreach (str_split($match[1] ?? '') as $letter) $column = $column * 26 + ord($letter) - 64;
                    if ($column < 1 || $column > 8) continue;
                    $value = $cell->xpath('./*[local-name()="v"]');
                    if ((string) $cell['t'] === 's') {
                        $value = $strings[(int) ($value[0] ?? -1)] ?? '';
                    } elseif ((string) $cell['t'] === 'inlineStr') {
                        $value = implode('', array_map('strval', $cell->xpath('.//*[local-name()="t"]')));
                    } else { $value = (string) ($value[0] ?? ''); }
                    $values[$column - 1] = trim($value);
                }
                if ($number === 1) {
                    $headerFound = true;
                    $headers = array_map(fn($value)=>str_replace(' ', '_', strtolower(trim($value))), $values);
                    if (($headers[3] ?? '') === 'qty') $headers[3] = 'jumlah';
                    if (($headers[5] ?? '') === 'tempat_barang') $headers[5] = 'lokasi';
                    if ($headers !== self::HEADERS) $fail('Header berbeda. Unduh template dan jangan ubah nama/urutan kolom.');
                } elseif (implode('', $values) !== '') {
                    $rows[] = ['row' => $number, 'values' => array_combine(self::HEADERS, $values)];
                }
            }
            if (!$headerFound) $fail('Header template pada baris 1 tidak ditemukan.');
            if (!$rows) $fail('Belum ada barang di sheet Data Barang.');
            return $rows;
        } finally { $zip->close(); }
    }

    public function import(User $actor, array $rows): int
    {
        abort_unless($actor->is_admin, 403);
        if (!$rows || count($rows) > 1000) throw ValidationException::withMessages(['file' => 'Isi 1–1.000 barang per import.']);
        $prepared = []; $codes = [];
        foreach ($rows as $row) {
            $v = $row['values'];
            $kind = match (strtolower($v['jenis'] ?? '')) { 'habis_pakai', 'habis pakai' => 'consumable', 'inventaris' => 'equipment', default => null };
            $data = ['code' => strtoupper(trim($v['kode'] ?? '')), 'name' => $v['nama_barang'] ?? '', 'kind' => $kind,
                'quantity' => $v['qty'] ?? $v['jumlah'] ?? '', 'unit' => $v['satuan'] ?? '', 'location' => $v['lokasi'] ?? '',
                'condition' => $v['kondisi'] ?? '', 'notes' => $v['catatan'] ?? ''];
            $validator = Validator::make($data, ['code' => ['required','string','max:100','unique:inventory_items,code'],
                'name' => ['required','string','max:255'], 'kind' => ['required','in:consumable,equipment'],
                'quantity' => ['required','integer','min:0','max:1000000'], 'unit' => ['required','string','max:30'],
                'location' => ['nullable','string','max:255'], 'condition' => ['required','in:Baik,Perlu diperiksa,Rusak'], 'notes' => ['nullable','string','max:2000']]);
            if ($validator->fails()) throw ValidationException::withMessages(['file' => 'Baris '.$row['row'].': '.implode(' ', $validator->errors()->all())]);
            if (isset($codes[$data['code']])) throw ValidationException::withMessages(['file' => 'Kode '.$data['code'].' muncul lebih dari sekali.']);
            $codes[$data['code']] = true; $prepared[] = $data;
        }
        return DB::transaction(function () use ($actor, $prepared) {
            foreach ($prepared as $data) {
                $quantity = (int) $data['quantity']; unset($data['quantity']);
                $item = InventoryItem::create($data);
                if ($quantity > 0) app(InventoryService::class)->record($actor, $item->id, 'receive', $quantity, $actor->name, 'Stok awal dari import Excel');
            }
            return count($prepared);
        });
    }
}
