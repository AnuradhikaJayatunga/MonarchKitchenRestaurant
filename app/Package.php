<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'package';
    protected $primaryKey = 'idpackage';

    public function CateringOrderItems()
    {
        return $this->hasMany(CateringOrderItems::class,'package_idpackage');
    }
}
