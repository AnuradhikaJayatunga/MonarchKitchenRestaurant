<?php


namespace App\Http\Controllers;

use App\CateringOrderIngrediant;
use App\CateringOrderIngrediantTemp;
use App\CateringOrderItems;
use App\DeliveryOrderIngrediant;
use App\DeliveryOrderIngrediantTemp;
use App\DeliveryOrderItems;
use App\Package;
use App\Product;
use App\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CateringOrderController extends Controller
{
    public function cateringOrder()
    {
        $ingredients = Product::where('status', 1)->get();
        $packages = Package::where('status', 1)->get();

        return view('order.catering-order', ['title' => ' Create Catering Menu', 'ingredients' => $ingredients, 'packages' => $packages]);
    }

    public function dcateringOrderLists()
    {
        $orders = CateringOrderItems::where('status', 1)->get();
        return view('order.catering-order-lists', ['title' => 'Catering Order Lists', 'orders' => $orders]);
    }

    public function ingredientTable()
    {
        $ingredients = CateringOrderIngrediantTemp::where('user_master_iduser_master', Auth::user()->iduser_master)->get();
        $tableData = '';
        if (count($ingredients) === 0) {
            $tableData .= "<tr>";
            $tableData .= "<td colspan='3' class='text-center'>" . 'Sorry No results Found.' . "</td>";
            $tableData .= "</tr>";
        } else {
            foreach ($ingredients as $ingredient) {
                $tableData .= "<tr>";
                $tableData .= "<td>" . $ingredient->Product->product_name . "</td>";
                $tableData .= "<td >" . number_format($ingredient->qty, 2) . "</td>";
                $tableData .= "<td>";
                $tableData .= "<button type='button'  class='btn btn-sm btn-danger  waves-effect waves-light' onClick='deleteCateringIngredient($ingredient->idcatering_order_ingrediant)'> ";
                $tableData .= " <i class=\"fa fa-trash\"></i>";
                $tableData .= "  </button>";
                $tableData .= " </td>";
                $tableData .= "</tr>";
            }
        }
        return $tableData;
    }

    public function getAvailableQty(Request $request)
    {
        try {
            $deliveryOrder = CateringOrderIngrediantTemp::where('user_master_iduser_master', Auth::user()->iduser_master)->sum('qty');
            $stockQty = Stock::where('product_idproduct', $request['id'])->where('status', 1)->sum('qty_available');
            return $stockQty - $deliveryOrder;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function saveIngredient(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = \Validator::make($request->all(), [

                'itemId' => 'required',
                'ingrediantQty' => 'required|not_in:0',
            ], [
                'itemId.required' => 'Item should be provided!',
                'ingrediantQty.required' => 'Qty should be provided!',
                'ingrediantQty.not_in' => 'Qty may not be 0!',
            ]);

            $checkQty = Stock::where('product_idproduct', $request['itemId'])->where('status', 1)->sum('qty_available');

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            if ($checkQty < $request['ingrediantQty']) {
                return response()->json(['qtyError' => 'Invalid Qty']);
            }

            $isExist = CateringOrderIngrediantTemp::where('product_idproduct', $request['itemId'])
                ->where('user_master_iduser_master', Auth::user()->iduser_master)
                ->first();

            if ($isExist !== null) {
                $isExist->qty += $request['ingrediantQty'];
                $isExist->save();
            } else {
                $save = new CateringOrderIngrediantTemp();
                $save->qty = $request['ingrediantQty'];
                $save->user_master_iduser_master = Auth::user()->iduser_master;
                $save->product_idproduct = $request['itemId'];
                $save->save();
            }
            DB::commit();
            return response()->json(['success' => 'Ingredient saved successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function saveCateringOrder(Request $request)
    {

        DB::beginTransaction();
        try {
            $validator = \Validator::make($request->all(), [
                'package' => 'required',
                'itemPrice' => 'required|not_in:0|gt:0',
                'itemQty' => 'required|not_in:0|gt:0',
                'packageName' => 'required',
                'image' => 'required',
            ], [
                'package.required' => 'Package should be provided!',
                'itemPrice.required' => 'Item Price should be provided!',
                'itemPrice.not_in' => 'Item Price may not be 0!',
                'itemPrice.gt' => 'Item Price may not be minus!',
                'itemQty.required' => 'Qty should be provided!',
                'itemQty.not_in' => 'Qty may not be 0!',
                'itemQty.qt' => 'Qty may not be minus!',
                'packageName.required' => 'Package Name should be provided!',
                'image.required' => 'Image should be provided!',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            // $checkIngredientAvailable = CateringOrderIngrediantTemp::where('user_master_iduser_master', Auth::user()->iduser_master)->count();
            // if ($checkIngredientAvailable === 0) {
            //     return response()->json(['ingredientError' => 'Please provide ingredient']);
            // }


            $imageName = time() . str_random(15) . '.' . $request->image->extension();
            $request->image->move(public_path('assets/images/orders'), $imageName);

            $record = new CateringOrderItems();
            $record->qty = $request['itemQty'];
            $record->image = $imageName;
            $record->name = $request['packageName'];
            $record->package_idpackage = $request['package'];
            $record->price = $request['itemPrice'];
            $record->description = $request['description'];
            $record->status = 1;
            $record->save();

            $cateringOrders = CateringOrderIngrediantTemp::where('user_master_iduser_master', Auth::user()->iduser_master)->get();
            foreach ($cateringOrders as $cateringOrder) {
                $qty = $cateringOrder->qty;
                $stockQty = Stock::where('status', 1)
                    ->where('qty_available', '>', 0)
                    ->where('product_idproduct', $cateringOrder->product_idproduct)->get();

                foreach ($stockQty as $stock) {
                    if ($stock->qty_available == 0) {
                        $stock->status = '0';
                        $stock->update();
                        $qty = 0;
                        break;
                    } else if ($stock->qty_available > $cateringOrder->qty) {
                        $stock->qty_available -= $qty;
                        $stock->save();
                        $qty = 0;
                        break;
                    } else if ($stock->qty_available == $cateringOrder->qty) {
                        $stock->qty_available -= $qty;
                        $stock->status = 0;
                        $stock->save();
                        $qty = 0;
                        break;
                    }
                }

                $save = new CateringOrderIngrediant();
                $save->qty = $cateringOrder->qty;
                $save->product_idproduct = $cateringOrder->product_idproduct;
                $save->catering_order_items_idcatering_order_items = $record->idcatering_order_items;
                $save->save();
                $cateringOrder->delete();
            }

            DB::commit();
            return response()->json(['success' => 'Delivery order saved successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteCateringIngredient(Request $request)
    {
        DB::beginTransaction();
        try {
            $record = CateringOrderIngrediantTemp::find($request['id']);
            $record->delete();
            DB::commit();
            return response()->json(['success' => 'ingredient deleted successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
