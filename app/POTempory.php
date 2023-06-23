<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class POTempory extends Model
{
    protected $table='po_tempory';
    protected $primaryKey='idpo_tempory';

    public function Product(){
        return $this->belongsTo(Product::class,'product_idproduct');
    }
    public function Category(){
        return $this->belongsTo(Category::class,'category_idcategory');
    }
}