<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class CateringOrderIngrediant extends Model
{
    protected $table = 'catering_order_ingrediant';
    protected $primaryKey = 'idcatering_order_ingrediant';

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
