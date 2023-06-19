<?php


namespace App\Http\Controllers;

use App\CateringOrderItems;
use App\DeliveryOrderItems;
use App\Invoice;
use App\MainCategory;
use App\MasterBooking;
use App\Order;
use App\Product;
use App\Stock;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function orderReport(Request $request)
    {

        $orderID = $request['orderID'];
        $date = $request['date'];
        $status = $request['status'];
        $type = $request['type'];

        $query = Order::query();

        if (!empty($orderID)) {
            $query = $query->where('idorder', $orderID);
        }
        if ($status != null) {
            $query = $query->where('status', $status);
        }
        if (!empty($type)) {
            $query = $query->where('type', $type);
        }
        if (!empty($date)) {
            $date = date('Y-m-d', strtotime($request['date']));
            $query = $query->where('date', $date);
        }

        $orders = $query->get();

        return view('reports.order-report', ['title' => 'Order Items Report', 'orders' => $orders]);
    }

    public function customerReport(Request $request)
    {
        $name = $request['name'];
        $contactNo = $request['contact_no'];
        $address = $request['address'];

        $query = User::query();

        if (!empty($name)) {
            $query = $query->where('first_name', 'LIKE', '%' . $name . '%')->orWhere('last_name', 'LIKE', '%' . $name . '%');
        }
        if (!empty($contactNo)) {
            $query = $query->where('contact_no', 'LIKE', '%' . $contactNo . '%');
        }
        if (!empty($address)) {
            $query = $query->where('address', 'LIKE', '%' . $address . '%');
        }

        $customers = $query->get();

        return view('reports.customer-report', ['title' => 'Customer Report', 'customers' => $customers]);
    }

    public function supplierReport(Request $request)
    {
        $poNo = $request['poNo'];
        $grnNo = $request['grnNo'];
        $item = $request['item'];

        $query = Supplier::query();

        if (!empty($poNo)) {
            $query = $query->whereHas('PurchaseOrder', function ($query) use ($poNo) {
                $query->where('idpurchase_order', $poNo);
            });
        }
        if (!empty($grnNo)) {
            $query = $query->whereHas('MasterGrn', function ($query) use ($grnNo) {
                $query->where('idmaster_grn', $grnNo);
            });
        }

        $suppliers = $query->get();

        return view('reports.supplier-report', ['title' => 'Supplier Report', 'suppliers' => $suppliers]);
    }

    public function productReport(Request $request)
    {

        $cateringOrderItems = CateringOrderItems::get()->map->formatData();
        $deliveryOrderItems = DeliveryOrderItems::get()->map->formatData();

        $allProducts = array_merge($cateringOrderItems->toArray(), $deliveryOrderItems->toArray());

        return view('reports.product-report', ['title' => 'Product Report', 'allProducts' => $allProducts]);
    }
}
