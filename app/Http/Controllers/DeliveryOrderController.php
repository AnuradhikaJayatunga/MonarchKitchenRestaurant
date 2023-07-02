<?php


namespace App\Http\Controllers;

use App\CateringOrderIngrediantTemp;
use App\DeliveryOrderIngrediant;
use App\DeliveryOrderIngrediantTemp;
use App\DeliveryOrderItems;
use App\Order;
use App\Product;
use App\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class DeliveryOrderController extends Controller
{
    public function deliveryOrder()
    {
        $ingredients = Product::where('status', 1)->get();
        return view('order.delivery-order', ['title' => 'Create Delivery and Reservation Menu', 'ingredients' => $ingredients]);
    }

    public function deliveryOrderLists()
    {
        $orders = DeliveryOrderItems::where('status', 1)->get();
        return view('order.delivery-order-lists', ['title' => 'Delivery and Reservation Items List', 'orders' => $orders]);
    }

    public function ingredientTable()
    {
        $ingredients = DeliveryOrderIngrediantTemp::where('user_master_iduser_master', Auth::user()->iduser_master)->get();
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
                $tableData .= "<button type='button'  class='btn btn-sm btn-danger  waves-effect waves-light' onClick='deleteIngredient($ingredient->iddelivery_order_ingrediant)'> ";
                $tableData .= " <i class=\"fa fa-trash\"></i>";
                $tableData .= "  </button>";
                $tableData .= " </td>";
                $tableData .= "</tr>";
            }
        }
        return $tableData;
    }

    public function deleteDeliveryIngredient(Request $request)
    {
        DB::beginTransaction();
        try {
            $record = DeliveryOrderIngrediantTemp::find($request['id']);
            $record->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAvailableQty(Request $request)
    {
        try {
            $deliveryOrder = DeliveryOrderIngrediantTemp::where('user_master_iduser_master', Auth::user()->iduser_master)->sum('qty');
            $stockQty = Stock::where('product_idproduct', $request['id'])->where('status', 1)->sum('qty_available');
            return $stockQty - $deliveryOrder;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteCateringIngredient(Request $request)
    {
        DB::beginTransaction();
        try {
            $record = DeliveryOrderIngrediantTemp::find($request['id']);
            $record->delete();
            DB::commit();
            return response()->json(['success' => 'ingredient deleted successfully']);
        } catch (Exception $e) {
            DB::rollBack();
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

            $isExist = DeliveryOrderIngrediantTemp::where('product_idproduct', $request['itemId'])
                ->where('user_master_iduser_master', Auth::user()->iduser_master)
                ->first();

            if ($isExist !== null) {
                $isExist->qty += $request['ingrediantQty'];
                $isExist->save();
            } else {
                $save = new DeliveryOrderIngrediantTemp();
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

    // Public function deleteDeliveryOrderItems(Request $request)
    // {

    //     DB::beginTransaction();
    //     try{
    //         $record = DeliveryOrderItems::find($request['id']);
    //         $record->delete();
    //         DB::commit();
    //     }catch (Exception $e){
    //         DB::rollBack();
    //         throw $e;
    //     }

    // }


    public function deleteOrderItems(Request $request)
    {
        $isOrderItemsUsed = Order::where('Order_idorder_items', $request['id'])->exists();
        if ($isOrderItemsUsed) {
            return response()->json(['error' => 'Item used in Order']);
        }
        DB::beginTransaction();
        try {
            $record = Order::find($request['id']);
            $record->delete();
            DB::commit();
            return response()->json(['success' => 'Item deleted successfully']);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        } 
    }

    public function saveDeliveryOrder(Request $request)
    {

        DB::beginTransaction();
        try {
            $validator = \Validator::make($request->all(), [
                'itemName' => 'required',
                'itemPrice' => 'required|not_in:0|gt:0',
                'itemQty' => 'required|not_in:0|gt:0',
                'image' => 'required',
            ], [
                'itemName.required' => 'Item Name should be provided!',
                'itemPrice.required' => 'Item Price should be provided!',
                'itemPrice.not_in' => 'Item Price may not be 0!',
                'itemPrice.gt' => 'Item Price may not minus!',
                'itemQty.required' => 'Item Qty should be provided!',
                'itemQty.not_in' => 'Item Qty may not be 0!',
                'itemQty.gt' => 'Item Qty may not be minus!',
                'image.required' => 'Image should be provided!',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            // $checkIngredientAvailable = DeliveryOrderIngrediantTemp::where('user_master_iduser_master', Auth::user()->iduser_master)->count();
            // if ($checkIngredientAvailable === 0) {
            //     return response()->json(['ingredientError' => 'Please provide ingredient']);
            // }

            $imageName = time() . str_random(15) . '.' . $request->image->extension();
            $request->image->move(public_path('assets/images/orders'), $imageName);

            $record = new DeliveryOrderItems();
            $record->qty = $request['itemQty'];
            $record->image = $imageName;
            $record->name = $request['itemName'];
            $record->item_price = $request['itemPrice'];
            $record->status = 1;
            $record->save();

            $deliveryOrders = DeliveryOrderIngrediantTemp::where('user_master_iduser_master', Auth::user()->iduser_master)->get();
            foreach ($deliveryOrders as $deliveryOrder) {
                $qty = $deliveryOrder->qty;
                $stockQty = Stock::where('status', 1)
                    ->where('qty_available', '>', 0)
                    ->where('product_idproduct', $deliveryOrder->product_idproduct)->get();

                foreach ($stockQty as $stock) {
                    if ($stock->qty_available == 0) {
                        $stock->status = '0';
                        $stock->update();
                        $qty = 0;
                        break;
                    } else if ($stock->qty_available > $deliveryOrder->qty) {
                        $stock->qty_available -= $qty;
                        $stock->save();
                        $qty = 0;
                        break;
                    } else if ($stock->qty_available == $deliveryOrder->qty) {
                        $stock->qty_available -= $qty;
                        $stock->status = 0;
                        $stock->save();
                        $qty = 0;
                        break;
                    }
                }

                $save = new DeliveryOrderIngrediant();
                $save->qty = $deliveryOrder->qty;
                $save->product_idproduct = $deliveryOrder->product_idproduct;
                $save->delivery_order_items_iddelivery_order_items = $record->iddelivery_order_items;
                $save->save();
                $deliveryOrder->delete();
            }

            DB::commit();
            return response()->json(['success' => 'Delivery order saved successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
