<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'idorder_items';

    public function DeliveryOrderItems()
    {
        return $this->belongsTo(DeliveryOrderItems::class, 'order_items_iddelivery_order_items');
    }

    public function PurchaseOrder()
    {
        return $this->hasMany(PurchaseOrder::class,'supplier_idsupplier');
    }

}
