<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InventoryMovement extends Model {
 protected $fillable=['inventory_item_id','user_id','loan_id','type','quantity','balance_after','person','notes','checkout_id','purpose','location_snapshot','due_date'];
 protected function casts(): array{return ['due_date'=>'date'];}
 public function checkout(){return $this->belongsTo(InventoryCheckout::class);}
 public function item(){return $this->belongsTo(InventoryItem::class,'inventory_item_id');}
 public function user(){return $this->belongsTo(User::class);}
 public function returns(){return $this->hasMany(self::class,'loan_id')->where('type','return');}
}
