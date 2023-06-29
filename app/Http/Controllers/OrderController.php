<?php


namespace App\Http\Controllers;

use App\CateringOrderItems;
use App\DeliveryOrderItems;
use App\Order;
use App\OrderItems;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class OrderController extends Controller
{

    public function pendingOrders()
    {
        $orders = Order::where('status', 0)->get();
        return view('order_lists.pending-order', ['title' => 'Pending Orders', 'orders' => $orders]);
    }

    public function acceptedOrders()
    {
        $orders = Order::where('status', 1)->get();
        $drivers = User::where('user_role_iduser_role', 2)->get();
        return view('order_lists.accepted-order', ['title' => 'Accepted Orders', 'orders' => $orders, 'drivers' => $drivers]);
    }

    public function completedOrders()
    {
        $orders = Order::where('status', 2)->get();
        return view('order_lists.completed-order', ['title' => 'Completed Orders', 'orders' => $orders]);
    }

    public function cancelOrders()
    {
        $orders = Order::where('status', 3)->get();
        return view('order_lists.canceled-order', ['title' => 'Canceled Orders', 'orders' => $orders]);
    }

    public function tasks()
    {
        if (Auth::user()->user_role_iduser_role == 1){
            $orders = Order::where('is_task_completed', 0)->where('driver_id', '!=',null)->where('type','!=','Reservation Order')->get();
        return view('tasks.tasks', ['title' => 'Pending Tasks', 'orders' => $orders]);
        }
       
        else{
            $orders = Order::where('is_task_completed', 0)->where('driver_id', Auth::user()->iduser_master)->get();
            return view('tasks.tasks', ['title' => 'Pending Tasks', 'orders' => $orders]);
        }
        
        
    }

    public function completedTasks()
    {
        if (Auth::user()->user_role_iduser_role == 1){
            $orders = Order::where('is_task_completed', 1)->get();
        return view('tasks.completed-tasks', ['title' => 'Completed Tasks', 'orders' => $orders]);
        }
       
        else{
            $orders = Order::where('is_task_completed', 1)->where('driver_id', Auth::user()->iduser_master)->get();
        return view('tasks.completed-tasks', ['title' => 'Completed Tasks', 'orders' => $orders]);
        }
        
    }

    public function pendingOrderCustomer()
    {
        $orders = Order::where('status', 0)->where('user_master_iduser_master', Auth::user()->iduser_master)->get();
        return view('customer_order_lists.pending-order', ['title' => 'Pending Orders', 'orders' => $orders]);
    }

    public function acceptedOrderCustomer()
    {
        $orders = Order::where('status', 1)->where('user_master_iduser_master', Auth::user()->iduser_master)->get();
        return view('customer_order_lists.accepted-order', ['title' => 'Accepted Orders', 'orders' => $orders]);
    }

    public function completedOrderCustomer()
    {
        $orders = Order::where('status', 2)->where('user_master_iduser_master', Auth::user()->iduser_master)->get();
        return view('customer_order_lists.completed-order', ['title' => 'Completed Orders', 'orders' => $orders]);
    }

    public function canceledOrderCustomer()
    {
        $orders = Order::where('status', 3)->where('user_master_iduser_master', Auth::user()->iduser_master)->get();
        return view('customer_order_lists.canceled-order', ['title' => 'Canceled Orders', 'orders' => $orders]);
    }

    public function cancelOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request['id'];

            $order = Order::find($id);
            $order->status = 3;
            $order->save();
            DB::commit();
        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
            return response()->json(['success' => 'Order Cancel']);
        }
    }

    public function viewOrderItems(Request $request)
    {
        $order = Order::find($request['id']);
        if ($order->type == 'Delivery Order' || $order->type == 'Reservation Order') {
            $items = OrderItems::where('order_idorder', $order->idorder)->get();
            $tableData = '';
            if (count($items) != 0) {
                foreach ($items as $item) {

                    $tableData .= "<tr>";
                    $tableData .= "<td>" . DeliveryOrderItems::find($item->id)->name . "</td>";

                    $tableData .= "<td>" . number_format($item->qty, 2) . "</td>";
                    $tableData .= "</tr>";
                }
            } else {
                $tableData = "<tr><td colspan='8' style='text-align: center'><b>Sorry No Results Found.</b></td></tr>";
            }
            return response()->json(['data' => $tableData]);
        } else if ($order->type == 'Catering Order') {
            $items = OrderItems::where('order_idorder', $order->idorder)->get();
            $tableData = '';
            if (count($items) != 0) {
                foreach ($items as $item) {

                    $tableData .= "<tr>";
                    $tableData .= "<td>" . CateringOrderItems::find($item->id)->name . "</td>";

                    $tableData .= "<td>" . number_format($item->qty, 2) . "</td>";
                    $tableData .= "</tr>";
                }
            } else {
                $tableData = "<tr><td colspan='8' style='text-align: center'><b>Sorry No Results Found.</b></td></tr>";
            }
            return response()->json(['data' => $tableData]);
        }
    }

    public function approvedOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $record = Order::find($request['id']);
            $record->status = 1;
            $record->sales_person_id = Auth::user()->iduser_master;
            $record->accepted_user_id = Auth::user()->iduser_master;
            $record->accept_date_time = Carbon::now()->format('Y-m-d H:i:s');
            $record->save();
            DB::commit();
            return response()->json(['success' => 'Order Accepted']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function completeOrder(Request $request)
    {

        DB::beginTransaction();
        try {
            $record = Order::find($request['id']);
            $record->status = 2;
            $record->save();
            DB::commit();
            return response()->json(['success' => 'Order Completed']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function completeTask(Request $request)
    {
        DB::beginTransaction();
        try {
            $record = Order::find($request['id']);
            $record->is_task_completed = 1;
            $record->save();
            DB::commit();
            return response()->json(['success' => 'Task Completed']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function assignDriver(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = \Validator::make($request->all(), [
                'driverId' => 'required',
            ], [
                'driverId.required' => 'Driver should be provided!'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $record = Order::find($request['hiddenOrderId']);
            $record->driver_id = $request['driverId'];
            $record->save();
            DB::commit();
            return response()->json(['success' => 'Order Completed']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function printInvoice($id)
    {
        $invoice = Order::find(intval($id));
        $data = [];
        $order = Order::find($id);
        if ($order->type == 'Delivery Order' || $order->type == 'Reservation Order') {
            $items = OrderItems::where('order_idorder', $order->idorder)->get();

            foreach ($items as $item) {
                $data[] = [
                    'name' => DeliveryOrderItems::find($item->id)->name,
                    'qty' => number_format($item->qty, 2)
                ];
            }
        } else if ($order->type == 'Catering Order') {
            $items = OrderItems::where('order_idorder', $order->idorder)->get();

            foreach ($items as $item) {
                $data[] = [
                    'name' => CateringOrderItems::find($item->id)->name,
                    'qty' => number_format($item->qty, 2)
                ];
            }
        }

        return view('print.print-invoice')->with(["invoice" => $invoice, 'data' => $data]);
    }

    public function getOrderDetails(Request $request)
    {
        try {
            $order = Order::find($request['id']);
            return ['order' => $order];
        } catch (Exception $th) {
            throw $th;
        }
    }
}
