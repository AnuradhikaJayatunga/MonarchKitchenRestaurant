<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use App\UserRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SecurityController extends Controller
{


    public function signin(Request $request)
    {
        try {
            $this->validate($request, ['username' => 'required', 'password' => 'required|min:3']);

            $advanceEncryption = (new  \App\MyResources\AdvanceEncryption($request->get('password'), "Nova6566", 256));
            Log::debug(json_encode($advanceEncryption));
            $user = User::where('user_name', $request->get('username'))
                ->where('password', $advanceEncryption->encrypt())->exists();
            if ($user === true) {
                $userData = User::where('user_name', $request->get('username'))
                    ->where('password', $advanceEncryption->encrypt())->first();
                if ($userData->status == 1) {
                    session(['userid' => $userData->idUser]);
                    Auth::login($userData);

                    return redirect('/index');
                } elseif ($userData->status == 0) {
                    return back()->with('warning', 'User has been suspended! Contact your System Administrator.');
                }
            } else {
                return back()->with('error', 'Incorrect login details! Check Email and Password');
            }
        } catch (Exception $exception) {
            return back()->with('warning', 'Somthing went wrong.');
        }
    }

    public function logoutNow(Request $request)
    {
        $request->session()->invalidate();
        return redirect('/signin');
    }
}
