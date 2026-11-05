<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InventoryItem extends Model {
 protected $fillable=['code','name','kind','unit','location','condition','notes','is_active','stock_verified'];
 protected function casts(): array{return ['quantity'=>'integer','is_active'=>'boolean','stock_verified'=>'boolean'];}
 public function movements(){return $this->hasMany(InventoryMovement::class);}
 public function lastLoan(){return $this->hasOne(InventoryMovement::class)->where('type','borrow')->latestOfMany();}
 public function lastReceipt(){return $this->hasOne(InventoryMovement::class)->where('type','receive')->latestOfMany();}
}
