<?php

namespace App\Http\Controllers;

use App\Models\{InventoryCheckout, InventoryItem};
use App\Services\{InventoryCheckoutService, InventoryRack};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicInventoryController extends Controller
{
    public function index()
    {
        return response()->view('inventory.racks', ['racks' => config('inventory.racks')])->header('Cache-Control', 'private, no-store');
    }

    public function rack(Request $request, string $rack)
    {
        abort_unless(InventoryRack::valid($rack), 404);
        $items = InventoryItem::where('is_active', true)->orderBy('name')
            ->get(['id', 'code', 'name', 'kind', 'unit', 'location', 'quantity', 'stock_verified'])
            ->filter(fn ($item) => InventoryRack::normalize($item->location) === $rack);
        $token = (string) Str::uuid();
        $tokens = $request->session()->get('inventory_forms', []);
        $tokens[$token] = $rack;
        $request->session()->put('inventory_forms', array_slice($tokens, -30, null, true));
        $dates = [];
        foreach (['today' => 'Hari ini', 'three_days' => '3 hari lagi', 'this_week' => 'Minggu ini'] as $key => $label) {
            $dates[$key] = ['label' => $label, 'date' => InventoryCheckoutService::dueDate($key)];
        }
        return response()->view('inventory.rack', compact('items', 'rack', 'token', 'dates'))->header('Cache-Control', 'private, no-store');
    }

    public function store(Request $request, string $rack, InventoryCheckoutService $service)
    {
        abort_unless(InventoryRack::valid($rack), 404);
        $data = $request->validate([
            'token' => ['required', 'uuid'], 'person' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:500'], 'notes' => ['nullable', 'string', 'max:2000'],
            'quantities' => ['required', 'array', 'max:500'],
            'quantities.*' => ['required', 'integer', 'min:0', 'max:1000000'],
            'return_choice' => ['nullable', 'in:today,three_days,this_week'],
        ]);
        abort_unless($request->session()->get('inventory_forms.'.$data['token']) === $rack, 419, 'Formulir kedaluwarsa. Buka kembali QR rak.');
        $checkout = $service->checkout($data['token'], $rack, $data);
        $receipts = $request->session()->get('inventory_receipts', []);
        $receipts[] = $checkout->id;
        $request->session()->put('inventory_receipts', array_slice(array_unique($receipts), -30));
        return redirect()->route('inventory.public.receipt', $checkout->id);
    }

    public function receipt(Request $request, string $checkout)
    {
        abort_unless(in_array($checkout, $request->session()->get('inventory_receipts', []), true), 404);
        $record = InventoryCheckout::with('movements.item')->findOrFail($checkout);
        return response()->view('inventory.receipt', ['checkout' => $record])->header('Cache-Control', 'private, no-store');
    }

    public function labels()
    {
        return response()->file(public_path('inventory-qr/label-qr-rak-ces.pdf'));
    }
}
