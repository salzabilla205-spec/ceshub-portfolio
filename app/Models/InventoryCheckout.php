<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCheckout extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'rack', 'person', 'purpose', 'notes'];

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class, 'checkout_id');
    }
}
