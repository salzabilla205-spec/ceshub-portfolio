<?php
namespace App\Services;
use App\Models\{InventoryItem,InventoryMovement,User};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class InventoryService {
 public function record(User $actor,int $itemId,string $type,int $quantity,string $person,?string $notes=null,?int $loanId=null): InventoryMovement {
  abort_unless($actor->is_admin,403);
  return DB::transaction(function()use($actor,$itemId,$type,$quantity,$person,$notes,$loanId){
   $item=InventoryItem::whereKey($itemId)->lockForUpdate()->firstOrFail();
   $fail=fn($message)=>throw ValidationException::withMessages(['quantity'=>$message]);
   if(!$item->is_active)$fail('Barang diarsipkan. Aktifkan dahulu sebelum transaksi.');
   if($quantity<1||trim($person)==='')$fail('Jumlah minimal satu dan nama pengguna wajib diisi.');
   if(!in_array($type,['receive','consume','borrow','return'],true))$fail('Transaksi tidak valid.');
   if(($type==='consume'&&$item->kind!=='consumable')||(in_array($type,['borrow','return'])&&$item->kind!=='equipment'))$fail('Transaksi tidak sesuai jenis barang.');
   if($type==='return'){
    $loan=InventoryMovement::whereKey($loanId)->where('inventory_item_id',$item->id)->where('type','borrow')->first();
    if(!$loan)$fail('Peminjaman tidak ditemukan untuk barang ini.');
    if($quantity>$loan->quantity-$loan->returns()->sum('quantity'))$fail('Jumlah melebihi sisa pinjaman.');
    $person=$loan->person;
   }else{$loanId=null;}
   $out=in_array($type,['consume','borrow'],true);
   if($out&&$item->stock_verified===false)$fail('Stok belum dihitung. Catat jumlah fisik dahulu.');
   if($out&&$quantity>$item->quantity)$fail('Stok tersedia tidak mencukupi.');
   $item->quantity += $out?-$quantity:$quantity;if($type==='receive')$item->stock_verified=true;$item->save();
   return $item->movements()->create(['user_id'=>$actor->id,'loan_id'=>$loanId,'type'=>$type,'quantity'=>$quantity,'balance_after'=>$item->quantity,'person'=>trim($person),'notes'=>$notes]);
  });
 }
}
