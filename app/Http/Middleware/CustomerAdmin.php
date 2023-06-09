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

class CustomerAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->user_role_iduser_role == 1 || Auth::user()->user_role_iduser_role == 3) {
            return $next($request);
        } else {
            return redirect('/index');
        }
    }
}
