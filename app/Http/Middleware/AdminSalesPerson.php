<?php

/**
 * Created by PhpStorm.
 * User: nimeshjayasankha
 * Date: 10/6/20
 * Time: 10:03 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminSalesPerson
{
    public function handle($request, Closure $next)
    {
        Log::debug(Auth::user()->user_role_iduser_role);
        if (Auth::user()->user_role_iduser_role == 1 || Auth::user()->user_role_iduser_role == 4) {

            return $next($request);
        } else {
            return redirect('/index');
        }
    }
}
