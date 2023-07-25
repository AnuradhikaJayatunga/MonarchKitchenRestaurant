<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class CateringOrderItems extends Model
{
    protected $table = 'catering_order_items';
    protected $primaryKey = 'idcatering_order_items';

    public function formatData()
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'category' => 'Catering Orders',
        ];
    }

    public function Package(){  
        return $this->belongsTo(Package::class);
    }

}
