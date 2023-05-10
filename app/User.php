<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    protected $table = 'user_master';
    protected  $primaryKey = 'iduser_master';

    public function userRole()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function Order()
    {
        return $this->belongsTo(Order::class, 'driver_id');
    }
}
