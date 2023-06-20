<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'idorder';


    public function User()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    
    public function acceptUser()
    {
        return $this->belongsTo(User::class, 'accepted_user_id');
    }
}
