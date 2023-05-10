<?php

/**
 * Created by PhpStorm.
 * User: AnuradhikaJayatunga VGS
 * Date: 3/30/2020
 * Time: 12:05 PM
 */

namespace App\Http\Controllers;


use App\Bank;
use App\BankAccount;
use App\Category;
use App\Customer;
use App\DailyCategory;
use App\DailyProductionName;
use App\Expense;
use App\ExpensesCategory;
use App\ItemCategory;
use App\ItemRegistry;
use App\Measurement;
use App\MetaMeasurement;
use App\Package;
use App\Product;
use App\ServiceCategory;
use App\ServicesList;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth');
    }
    public function activateDeactivate(Request $request)
    {
        $id = $request['id'];
        $table = $request['table'];

        if ($table == "category") {

            $table = Category::find($id);
            if ($table->status == 1) {
                $table->status = 0;
            } else {
                $table->status = 1;
            }
            $table->update();
        }

        if ($table == "product") {

            $table = Product::find($id);
            if ($table->status == 1) {
                $table->status = 0;
            } else {
                $table->status = 1;
            }
            $table->update();
        }
        if ($table == "supplier") {

            $table = Supplier::find($id);
            if ($table->status == 1) {
                $table->status = 0;
            } else {
                $table->status = 1;
            }
            $table->update();
        }
        if ($table == "package") {

            $table = Package::find($id);
            if ($table->status == 1) {
                $table->status = 0;
            } else {
                $table->status = 1;
            }
            $table->update();
        }
        if ($table == "user_master") {

            $table = User::find($id);
            if ($table->status == 1) {
                $table->status = 0;
            } else {
                $table->status = 1;
            }
            $table->update();
        }
    }
}
