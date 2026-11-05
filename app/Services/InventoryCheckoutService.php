<?php

namespace App\Services;

use App\Models\{InventoryCheckout, InventoryItem};
use Carbon\CarbonImmutable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryCheckoutService
{
    public static function dueDate(string $choice): string
    {
        $today = CarbonImmutable::today('Asia/Jakarta');
        return match ($choice) {
            'today' => $today->toDateString(),
            'three_days' => $today->addDays(3)->toDateString(),
            'this_week' => $today->endOfWeek(CarbonImmutable::SUNDAY)->toDateString(),
            default => throw ValidationException::withMessages(['return_choice' => 'Pilih rencana pengembalian.']),
        };
    }

    public function checkout(string $id, string $rack, array $data): InventoryCheckout
    {
        abort_unless(InventoryRack::valid($rack), 404);
        $person = trim($data['person'] ?? '');
        $purpose = trim($data['purpose'] ?? '');
        if ($person === '' || $purpose === '') {
            throw ValidationException::withMessages(['person' => 'Nama dan keperluan wajib diisi.']);
        }
        $quantities = [];
        foreach ($data['quantities'] ?? [] as $itemId => $quantity) {
            if (!ctype_digit((string) $itemId) || (int) $itemId < 1 || filter_var($quantity, FILTER_VALIDATE_INT) === false || $quantity < 0 || $quantity > 1000000) {
                throw ValidationException::withMessages(['quantities' => 'Jumlah barang tidak valid.']);
            }
            if ($quantity > 0) $quantities[(int) $itemId] = (int) $quantity;
        }
        if (!$quantities || count($quantities) > 50) {
            throw ValidationException::withMessages(['quantities' => 'Pilih minimal satu barang, maksimal 50 jenis.']);
        }
        ksort($quantities);

        try {
            return DB::transaction(function () use ($id, $rack, $data, $person, $purpose, $quantities) {
                if ($existing = InventoryCheckout::find($id)) return $existing;
                $checkout = InventoryCheckout::create([
                    'id' => $id, 'rack' => $rack, 'person' => $person,
                    'purpose' => $purpose, 'notes' => $data['notes'] ?? null,
                ]);
                // Stable lock order and a single transaction prevent overselling and partial carts.
                foreach ($quantities as $itemId => $quantity) {
                    $item = InventoryItem::whereKey($itemId)->lockForUpdate()->first();
                    if (!$item || !$item->is_active || InventoryRack::normalize($item->location) !== $rack || !in_array($item->kind, ['equipment', 'consumable'], true)) {
                        throw ValidationException::withMessages(['quantities' => 'Ada barang yang tidak tersedia di rak ini. Muat ulang daftar barang.']);
                    }
                    if ($item->stock_verified === false) {
                        throw ValidationException::withMessages(['quantities' => 'Jumlah '.$item->name.' belum dihitung oleh pengelola.']);
                    }
                    if ($quantity > $item->quantity) {
                        throw ValidationException::withMessages(['quantities' => 'Stok '.$item->name.' tinggal '.$item->quantity.' '.$item->unit.'. Sesuaikan jumlahnya.']);
                    }
                    $dueDate = $item->kind === 'equipment' ? self::dueDate($data['return_choice'] ?? '') : null;
                    $item->quantity -= $quantity;
                    $item->save();
                    $item->movements()->create([
                        'user_id' => null, 'checkout_id' => $id,
                        'type' => $item->kind === 'equipment' ? 'borrow' : 'consume',
                        'quantity' => $quantity, 'balance_after' => $item->quantity,
                        'person' => $person, 'purpose' => $purpose, 'notes' => $data['notes'] ?? null,
                        'location_snapshot' => $item->location, 'due_date' => $dueDate,
                    ]);
                }
                return $checkout;
            }, 3);
        } catch (QueryException $exception) {
            // Concurrent resubmission of the same form uses one unique checkout UUID.
            if (in_array((string) $exception->getCode(), ['23000', '23505'], true) && ($existing = InventoryCheckout::find($id))) return $existing;
            throw $exception;
        }
    }
}
