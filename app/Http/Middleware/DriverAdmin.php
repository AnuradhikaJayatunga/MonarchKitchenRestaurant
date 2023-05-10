<?php

/**
 * Created by PhpStorm.
 * User: AnuradhikaJayatungajayasankha
 * Date: 10/6/20
 * Time: 10:03 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DriverAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->user_role_iduser_role == 1 || Auth::user()->user_role_iduser_role == 2) {
            return $next($request);
        } else {
            return redirect('/index');
        }
    }
}
