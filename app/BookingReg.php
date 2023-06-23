<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class BookingReg extends Model
{
    protected $table='booking_reg';
    protected $primaryKey='idbooking_reg';

    public function Product(){
        return $this->belongsTo(Product::class,'product_idproduct');
    }

}