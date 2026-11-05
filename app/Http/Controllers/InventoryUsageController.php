<?php
namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryUsageController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['type' => ['nullable','in:borrow,consume']]);
        $type = $request->input('type', 'borrow');
        $kind = $type === 'borrow' ? 'equipment' : 'consumable';
        $items = InventoryItem::where('is_active', true)->where('kind', $kind)->orderBy('name')->get();
        return view('inventory.usage', compact('items','type'));
    }

    public function store(Request $request, InventoryService $inventory)
    {
        $data = $request->validate([
            'type' => ['required','in:borrow,consume'], 'item_id' => ['required','integer','exists:inventory_items,id'],
            'quantity' => ['required','integer','min:1','max:1000000'], 'person' => ['required','string','max:255'],
            'notes' => ['required','string','max:2000'],
        ]);
        $movement = $inventory->record($request->user(), (int) $data['item_id'], $data['type'], (int) $data['quantity'], $data['person'], $data['notes']);
        $message = $data['type'] === 'borrow' ? 'Peminjaman tercatat.' : 'Pengambilan BHP tercatat.';
        return redirect()->route('inventory.usage', ['type' => $data['type']])->with('inventory_success', $message.' Sisa stok tersedia: '.$movement->balance_after.' '.$movement->item->unit.'.');
    }
}
