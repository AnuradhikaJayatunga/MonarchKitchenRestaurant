<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class ItemInvTemp extends Model
{
    protected $table='item_inv_temp';
    protected $primaryKey='iditem_inv_temp';

    public function CategoryPrice(){
        return $this->hasMany(CategoryPrice::class,'main_category_idmain_category');
    }

}