<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'idcart';

    public function deliveryOrderItems()
    {
        return $this->belongsTo(DeliveryOrderItems::class);
    }
}
