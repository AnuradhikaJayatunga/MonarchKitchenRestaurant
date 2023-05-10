<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class CateringOrderIngrediantTemp extends Model
{
    protected $table = 'catering_order_ingrediant_temp';
    protected $primaryKey = 'idcatering_order_ingrediant';

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
