<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class DeliveryOrderIngrediant extends Model
{
    protected $table = 'delivery_order_ingrediant';
    protected $primaryKey = 'iddelivery_order_ingrediant';

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}