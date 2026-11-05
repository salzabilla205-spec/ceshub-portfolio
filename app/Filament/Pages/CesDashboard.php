<?php
namespace App\Filament\Pages;
use Filament\Pages\Dashboard;
class CesDashboard extends Dashboard {
    public function getSubheading(): ?string { return 'Kelola layanan CES.'; }

 protected static ?string $title='Beranda Admin';
 protected static ?string $navigationLabel='Beranda Admin';
 protected string $view='filament.pages.ces-studio-dashboard';
 public function getWidgets(): array {return [];}
 public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable | null {return 'Beranda Admin';}
 protected function getHeaderActions(): array {return [\App\Filament\Actions\InventoryUsageAction::make('borrow'),\App\Filament\Actions\InventoryUsageAction::make('consume')];}
 protected function getViewData(): array
 {
  return [
   'itemCount' => \App\Models\InventoryItem::where('is_active',true)->count(),
   'publishedProjects' => \App\Models\Project::where('status','published')->count(),
   'activeLoans' => \App\Models\InventoryMovement::where('type','borrow')->whereRaw("quantity > (select coalesce(sum(r.quantity), 0) from inventory_movements as r where r.loan_id = inventory_movements.id and r.type = ?)", ['return'])->count(),
   'emptyConsumables' => \App\Models\InventoryItem::where('is_active',true)->where('kind','consumable')->where('quantity',0)->orderBy('name')->get(['name','unit','location']),
   'recentMovements' => \App\Models\InventoryMovement::with('item:id,name,unit')->latest('id')->limit(5)->get(),
   'stockItems' => \App\Models\InventoryItem::where('is_active',true)->where('kind','consumable')
    ->withSum(['movements as received_quantity'=>fn($query)=>$query->where('type','receive')],'quantity')->orderBy('quantity')->orderBy('location')->orderBy('name')->limit(5)->get(),
  ];
 }
}
