<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $table='main_category';
    protected $primaryKey='idmain_category';

    public function CategoryPrice(){
        return $this->hasMany(CategoryPrice::class,'main_category_idmain_category');
    }

}