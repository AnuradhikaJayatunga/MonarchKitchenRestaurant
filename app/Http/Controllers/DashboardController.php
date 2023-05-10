<?php


namespace App\Http\Controllers;

use App\BookingReg;
use App\CategoryPrice;
use App\Mail\PendingOrder;
use App\MainCategory;
use App\MasterBooking;
use App\Order;
use App\TempBooking;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {


        if (Auth::user()->user_role_iduser_role == 3) {
            $Pending = Order::where('status', 0)->where('user_master_iduser_master', Auth::user()->iduser_master)->count('idorder');
            $accepted = Order::where('status', 1)->where('user_master_iduser_master', Auth::user()->iduser_master)->count('idorder');
            $completed = Order::where('status', 2)->where('user_master_iduser_master', Auth::user()->iduser_master)->count('idorder');

            return view('index', ['title' => 'Dashboard', 'Pending' => $Pending, 'accepted' => $accepted, 'completed' => $completed]);
        } else if (Auth::user()->user_role_iduser_role == 1 || Auth::user()->user_role_iduser_role == 4) {
            $Pending = Order::where('status', 0)->count('idorder');
            $accepted = Order::where('status', 1)->count('idorder');
            $completed = Order::where('status', 2)->count('idorder');

            return view('index', ['title' => 'Dashboard', 'Pending' => $Pending, 'accepted' => $accepted, 'completed' => $completed]);
        } else if (Auth::user()->user_role_iduser_role == 2) {
            $Pending = Order::where('is_task_completed', 0)->where('driver_id', Auth::user()->iduser_master)->count('idorder');
            $completed = Order::where('is_task_completed', 1)->where('driver_id', Auth::user()->iduser_master)->count('idorder');
            return view('index', ['title' => 'Dashboard', 'Pending' => $Pending, 'completed' => $completed]);
        }
    }
}
