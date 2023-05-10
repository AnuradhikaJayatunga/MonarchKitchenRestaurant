<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class DeliveryOrderItems extends Model
{
    protected $table = 'delivery_order_items';
    protected $primaryKey = 'iddelivery_order_items';

    public function cart()
    {
        return $this->hasMany(Cart::class, 'delivery_order_items_iddelivery_order_items');
    }

    public function formatData()
    {
        return [
            'name' => $this->name,
            'price' => $this->item_price,
            'category' => 'Delivery/Table Products',
        ];
    }
}
