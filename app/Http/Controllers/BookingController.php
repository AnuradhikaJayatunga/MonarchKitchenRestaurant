<?php


namespace App\Http\Controllers;


use App\Cart;
use App\CateringOrderItems;
use App\DeliveryOrderItems;
use App\Order;
use App\OrderItems;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;

class BookingController extends Controller
{
    public function deliveryOrder()
    {
        $orders = DeliveryOrderItems::get();
        return view('booking.delivery-order', ['title' => 'Delivery Order', 'orders' => $orders]);
    }


    public function addToCart(Request $request)
    {
        DB::beginTransaction();
        try {
            $cart = Cart::where('delivery_order_items_iddelivery_order_items', $request['id'])->where('type', 1)
                ->where('user_master_iduser_master', Auth::user()->iduser_master)
                ->first();

            if ($cart !== null) {
                $cart->qty += 1;
                $cart->save();
            } else {
                $record = new Cart();
                $record->delivery_order_items_iddelivery_order_items = $request['id'];
                $record->qty = 1;
                $record->type = 1;
                $record->user_master_iduser_master = Auth::user()->iduser_master;
                $record->save();
            }
            DB::commit();
            return response()->json(['success' => 'added to the cart']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cart()
    {
        $carts = Cart::where('user_master_iduser_master', Auth::user()->iduser_master)->where('type', 1)->get();
        $user = User::find(Auth::user()->iduser_master);
        $name = $user->first_name . ' ' . $user->last_name;
        $address = '';
        return view('cart.cart', ['title' => 'Cart', 'carts' => $carts, 'name' => $name, 'address' => $address]);
    }

    public function changeQty(Request $request)
    {
        DB::beginTransaction();
        try {
            $record = Cart::find($request['id']);
            if ($request['type'] === 'plus') {
                if ($record->deliveryOrderItems->qty < $record->qty + 1) {
                    return $record->deliveryOrderItems->qty;
                } else {
                    $record->qty += 1;
                    $record->save();
                    DB::commit();
                    return $record->qty;
                }
            } else if ($request['type'] === 'minus') {
                if ($record->qty - 1 < 1) {
                    $total = $this->total();
                    return 1;
                } else {
                    $record->qty -= 1;
                    $record->save();
                    DB::commit();
                    return $record->qty;
                }
            }
            return 0;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTotalCost()
    {
        $carts = Cart::where('user_master_iduser_master', Auth::user()->iduser_master)->get();
        $itemCosts = 0;
        foreach ($carts as $cart) {
            $itemCosts += $cart->qty * $cart->deliveryOrderItems->item_price;
        }
        return ['itemPrice' => number_format($itemCosts, 2), 'total' => number_format($itemCosts + env('DELIVERY_CHARGE'), 2)];
    }

    public function deleteChartRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            $record = Cart::find($request['id']);
            $record->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function payDeliveryOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = \Validator::make($request->all(), [

                'name' => 'required',
                'address' => 'required',
                'paymentType' => 'required',
            ], [
                'name.required' => 'Name should be provided!',
                'address.required' => 'Address should be provided!',
                'paymentType.required' => 'Payment Type should be provided!',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $carts = Cart::where('user_master_iduser_master', Auth::user()->iduser_master)->get();
            foreach ($carts as $cart) {
                $deliveryItem = DeliveryOrderItems::find($cart->delivery_order_items_iddelivery_order_items);
                if ($deliveryItem->qty < $cart->qty) {
                    return response()->json(['qty_error' => 'Not enough quantity for product ' . $deliveryItem->name]);
                }
            }

            $total = 0;
            foreach ($carts as $cart) {
                $total += $cart->qty * $cart->deliveryOrderItems->item_price;
            }

            $record = new Order();
            $record->total_cost = $total;
            $record->name = $request['name'];
            $record->address = $request['address'];
            $record->status = 0;
            $record->date = Carbon::now()->format('y-m-d');
            $record->type = 'Delivery Order';
            $record->user_master_iduser_master = Auth::user()->iduser_master;
            $record->save();

            foreach ($carts as $cart) {
                $stock = DeliveryOrderItems::find($cart->delivery_order_items_iddelivery_order_items);
                if ($stock->qty == 0) {
                    $stock->status = '0';
                    $stock->update();
                } else if ($cart->qty < $stock->qty) {
                    $stock->qty -= $cart->qty;
                    $stock->save();
                } else if ($cart->qty == $stock->qty) {
                    $stock->qty -= $cart->qty;
                    $stock->status = 0;
                    $stock->save();
                }

                $item = new OrderItems();
                $item->id = $cart->delivery_order_items_iddelivery_order_items;
                $item->qty = $cart->qty;
                $item->order_idorder = $record->idorder;
                $item->save();

                $cart->delete();
            }
            DB::commit();
            return response()->json(['success' => 'Order saved']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cateringOrders()
    {
        $orders = CateringOrderItems::where('status', 1)->get();
        return view('booking.catering-order', ['title' => 'Catering Order', 'orders' => $orders]);
    }

    public function placeCateringOrder(Request $request)
    {
        $order = CateringOrderItems::find($request['idOrder']);
        return view('booking.place-catering-order', ['id' => $request['idOrder'], 'title' => 'Place Catering Order', 'order' => $order]);
    }

    public function getTotalCostWithExtra(Request $request)
    {
        $order = CateringOrderItems::find($request['id']);
        $total = number_format($order->price + env('EXTRA_CHARGE'), 2);
        return $total;
    }

    public function payCateringOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = \Validator::make($request->all(), [

                'noOfPersons' => 'required|not_in:0|gt:0',
                'date' => 'required',
                'time' => 'required',
                'address' => 'required',
                
            ], [
                'noOfPersons.required' => 'Quantity should be provided!',
                'date.required' => 'Date should be provided!',
                'time.required' => 'Date should be provided!',
                'noOfPersons.not_in' => 'c may not be 0!',
                'noOfPersons.not_in' => 'Quantity may not be 0!',
                'noOfPersons.gt' => 'Quantity may not be minus!',
                'address.required' => 'Address should be provided!'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }
            $date = $request['date'];
            $time = $request['time'];
            $address = $address['address'];
            $cateringOrderStartTime = Carbon::parse('06:00')->format('H:i');
            $cateringOrderEndTime = Carbon::parse('21:00')->format('H:i');
            $todayDate = Carbon::now()->format('Y-m-d');
            $timeNow = Carbon::now()->format('H:i');

            if ($date < $todayDate) {
                return response()->json(['error' => 'Invalid date']);
            }

            if ($address===Null) {
                return response()->json(['error'=>'Address should be provided']);
            }

             if ($cateringOrderStartTime > $time || $cateringOrderEndTime < $time) {
                return response()->json(['error' => 'Invalid Time']);
            }

            if ($time < $timeNow && $date  <= $todayDate) {
                return response()->json(['error' => 'Invalid time ? Invalid Date.']);
            }
            if ($request['noOfPersons'] > 150) {
                return response()->json(['error' => 'Quantity should be less than 150']);
            }
            if ($request['noOfPersons'] < 50) {
                return response()->json(['error' => 'Minimum Quantity should be 50 ']);
            }
           
            $order = CateringOrderItems::find($request['cateringItemId']);
            if ($order->qty === 0) {
                return response()->json(['error' => 'Quantity not available']);
            }

            if ($request['noOfPersons'] > $order->qty) {
                return response()->json(['error' => 'Quantity not available']);
            }

            $record = new Order();
            $record->total_cost = $order->price * $request['noOfPersons'];
            $record->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            $record->address = $request['address'];
            $record->date = $date;
            $record->time = $time;
            $record->extra_item = $request['extraItem'];
            $record->status = 0;
            $record->type = 'Catering Order';
            $record->no_of_persons = $request['noOfPersons'];
            $record->user_master_iduser_master = Auth::user()->iduser_master;
            $record->save();


            $item = new OrderItems();
            $item->id = $order->idcatering_order_items;
            $item->qty = $request['noOfPersons'];
            $item->order_idorder = $record->idorder;
            $item->save();


            if ($order->qty == 0) {
                $order->status = '0';
                $order->update();
            } else if (1 < $order->qty) {
                $order->qty -= $request['noOfPersons'];
                $order->save();
            } else if (1 == $order->qty) {
                $order->qty -= 1;
                $order->status = 0;
                $order->save();
            }

            DB::commit();
            return response()->json(['success' => 'Order Saved']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reservationOrders()
    {
        $items = DeliveryOrderItems::where('status', 1)->get();
        return view('booking.reservation-orders', ['title' => 'Reservation Order', 'items' => $items]);
    }

    public function getProductDetails(Request $request)
    {
        try {
            $orderItem = DeliveryOrderItems::find($request['id']);
            $caryQty = Cart::sum('qty');
            return response()->json(['item_price' => $orderItem->item_price, 'qty' => $orderItem->qty - $caryQty]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getReservationTable()
    {
        $items = Cart::where('user_master_iduser_master', Auth::user()->iduser_master)->where('type', 2)->get();
        $tableData = '';
        $total = 0;
        if (count($items) != 0) {
            foreach ($items as $item) {

                $total += $item->deliveryOrderItems->item_price * $item->qty;
                $tableData .= "<tr>";
                $tableData .= "<td>" . $item->deliveryOrderItems->name . "</td>";

                $tableData .= "<td>" . number_format($item->qty, 2) . "</td>";
                $tableData .= "<td style=\"text-align: right\">"
                    . number_format($item->deliveryOrderItems->item_price * $item->qty, 2) . "</td>";
                $tableData .= "<td style='text-align: right'>";
                $tableData .= "<button type='button'  class='btn btn-sm btn-danger  waves-effect waves-light' onClick='deleteItem($item->idcart)' > ";
                $tableData .= " <i class=\"fa fa-edit\"></i>";
                $tableData .= "  </button>";
                $tableData .= " </td>";
                $tableData .= "</tr>";
            }
        } else {
            $tableData = "<tr><td colspan='8' style='text-align: center'><b>Sorry No Results Found.</b></td></tr>";
        }

        return response()->json(['total' => $total, 'tableData' => $tableData]);
    }

    public function addReservationItem(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = \Validator::make($request->all(), [
                'productId' => 'required',
                'qty' => 'required|not_in:0|gt:0'
            ], [
                'productId.required' => 'Product should be provided!',
                'qty.required' => 'Qty should be provided!',
                'qty.not_in' => 'Qty may not be 0!',
                'qty.gt' => 'Qty may not minus!'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $cart = Cart::where('delivery_order_items_iddelivery_order_items', $request['productId'])->where('type', 2)
                ->where('user_master_iduser_master', Auth::user()->iduser_master)
                ->first();

            if ($cart !== null) {
                $cart->qty += 1;
                $cart->save();
            } else {
                $record = new Cart();
                $record->delivery_order_items_iddelivery_order_items = $request['productId'];
                $record->qty = $request['qty'];
                $record->type = 2;
                $record->user_master_iduser_master = Auth::user()->iduser_master;
                $record->save();
            }
            DB::commit();
            return response()->json(['success' => 'Item saved']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function saveReservation(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = \Validator::make($request->all(), [
                'date' => 'required',
                'time' => 'required',
                'tableNo' => 'required',
                'noOfPersons' => 'required|not_in:0|gt:0|min:2',

            ], [
                'productId.required' => 'Product should be provided!',
                'tableNo.required' => 'Table No should be provided!',
                'date.required' => 'Date should be provided!',
                'time.required' => 'Time should be provided!',
                'noOfPersons.required' => 'Quantity should be provided!',
                'noOfPersons.not_in' => 'Quantity may not be 0!',
                'noOfPersons.gt' => 'Quantity may not minus!',
                'noOfPersons.min' => 'Quantity should be greater than two!',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $date = $request['date'];
            $time = $request['time'];
            $cateringOrderStartTime = Carbon::parse('11:00')->format('H:i');
            $cateringOrderEndTime = Carbon::parse('22:00')->format('H:i');
            $todayDate = Carbon::now()->format('Y-m-d');
            $timeNow = Carbon::now()->format('H:i');

            if ($date < $todayDate) {
                return response()->json(['error' => 'Invalid date']);
            }

            if ($cateringOrderStartTime > $time || $cateringOrderEndTime < $time) {
                return response()->json(['error' => 'Invalid Time']);
            }
            
            if ($time < $timeNow && $date  <= $todayDate) {
                return response()->json(['error' => 'Invalid time ? cannot order for lunch.']);
            }
            if ($request['noOfPersons'] > 50) {
                return response()->json(['error' => 'Quantity should be less than 50']);
            }


            $ifOrderAvailable = Order::where('date', $date)->where('time', $time)->where('table', $request['tableNo'])->exists();
            if ($ifOrderAvailable) {
                return response()->json(['error' => 'Sorry!,Already booked!']);
            }

            $carts = Cart::where('user_master_iduser_master', Auth::user()->iduser_master)->get();
            foreach ($carts as $cart) {
                $deliveryItem = DeliveryOrderItems::find($cart->delivery_order_items_iddelivery_order_items);
                if ($deliveryItem->qty < $cart->qty) {
                    return response()->json(['error' => 'Not enough quantity for product ' . $deliveryItem->name]);
                }
            }

            $total = 0;
            foreach ($carts as $cart) {
                $total += $cart->qty * $cart->deliveryOrderItems->item_price;
            }

            $record = new Order();
            $record->total_cost = $total;
            $record->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;;
            $record->address = null;
            $record->table = $request['tableNo'];
            $record->date = $date;
            $record->extra_item = $request['extraItem'];
            $record->time = $time;
            $record->no_of_persons = $request['noOfPersons'];
            $record->status = 0;
            $record->type = 'Reservation Order';
            $record->user_master_iduser_master = Auth::user()->iduser_master;
            $record->save();

            foreach ($carts as $cart) {
                $stock = DeliveryOrderItems::find($cart->delivery_order_items_iddelivery_order_items);
                if ($stock->qty == 0) {
                    $stock->status = '0';
                    $stock->update();
                } else if ($cart->qty < $stock->qty) {
                    $stock->qty -= $cart->qty;
                    $stock->save();
                } else if ($cart->qty == $stock->qty) {
                    $stock->qty -= $cart->qty;
                    $stock->status = 0;
                    $stock->save();
                }

                $item = new OrderItems();
                $item->id = $cart->delivery_order_items_iddelivery_order_items;
                $item->qty = $cart->qty;
                $item->order_idorder = $record->idorder;
                $item->save();

                $cart->delete();
            }
            DB::commit();
            return response()->json(['success' => 'Reservation saved']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function editCatering(Request $request)
    {
        $validator = \Validator::make($request->all(), [

            'noOfPersons' => 'required|not_in:0|gt:49',
            'date' => 'required',
            'time' => 'required',
            'address' => 'required', 
            
        ], [
            'noOfPersons.required' => 'Quantity should be provided!',
            'date.required' => 'Date should be provided!',
            'time.required' => 'Date should be provided!',
            'noOfPersons.not_in' => 'Quantity may not be 0!',
            'noOfPersons.gt' => 'Quantity may not be less than 50!',
            'address.required'=> 'Address should be Provided!',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $date = $request['date'];
        $time = $request['time'];
        
        $todayDate = Carbon::now()->format('Y-m-d');
        $timeNow = Carbon::now()->format('H:i');
        $address = $request['address'];
       

        if ($date < $todayDate) {
            return response()->json(['error' => 'Invalid date']);
        }

        if ($time < $timeNow && $date  <= $todayDate) {
            return response()->json(['error' => 'Invalid time ? Invalid date.']);
        }
        if ($request['noOfPersons'] > 150) {
            return response()->json(['error' => 'Quantity should be less than 150']);
        }

        if ($address===Null) {
            return response()->json(['error'=>'Address should be provided']);
        }
        
        $orderItem = OrderItems::where('order_idorder', $request['hiddenCateringOrderId'])->first();



        $order = CateringOrderItems::find($orderItem->id);
        if ($order->qty === 0) {
            return response()->json(['error' => 'Quantity not available']);
        }

        if ($request['noOfPersons'] > $order->qty) {
            return response()->json(['error' => 'Quantity not available']);
        }
        

        $singleOrder = Order::find($request['hiddenCateringOrderId']);

        $itemDiff = $request['noOfPersons'] - $singleOrder->no_of_persons;

        DB::beginTransaction();
        try {

            if ($itemDiff != 0 && $itemDiff > 0) {
                $order->qty -= $itemDiff;
                $order->save();
            }

            if ($itemDiff < 0 && $itemDiff != 0) {
                $order->qty += abs($itemDiff);
                $order->save();
            }

            $record = Order::find($request['hiddenCateringOrderId']);
            $record->total_cost = $order->price * $request['noOfPersons'];
            $record->date = $date;
            $record->time = $time;
            $record->address = $address;
            $record->extra_item = $request['extraItem'];
            $record->type = 'Catering Order';
            $record->no_of_persons = $request['noOfPersons'];
            $record->save();

            $orderItem->qty = $request['noOfPersons'];
            $orderItem->save();

            DB::commit();
            return response()->json(['success' => 'Order Edited Successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
