<?php


namespace App\Http\Controllers;

use App\PurchaseOrder;
use App\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function suppliersIndex(Request $request)
    {
        $suppliers = Supplier::orderBy('idSupplier', 'DESC')->get();

        return view('supplier.suppliers', ['title' => 'Supplier Management', 'suppliers' => $suppliers]);
    }

    public function deleteSupplier(Request $request)
    {

        $isSupplierUsed = PurchaseOrder::where('supplier_idsupplier', $request['id'])->exists();
        if ($isSupplierUsed) {
            return response()->json(['error' => 'Supplier used in PO']);
        }
        DB::beginTransaction();
        try {
            $record = Supplier::find($request['id']);
            $record->delete();
            DB::commit();
            return response()->json(['success' => 'Supplier deleted successfully']);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        } 
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            $supplierName = $request['supplierName'];
            $contactNo1 = $request['contactNo1'];
            $email = $request['email'];
            $address = $request['address'];
            $bankName = $request['bankName'];
            $accountNo = $request['accountNo'];

            $validator = \Validator::make($request->all(), [

                'supplierName' => 'required|max:45',
                'contactNo1' => 'required|min:9|max:9',
               'bankName' => 'required|max:45',
                'accountNo' => 'required|min:15||max:25',
            ], [
                'supplierName.required' => 'Supplier Name should be provided!',
                'supplierName.max' => 'Supplier Name must be less than 255 characters long.',
                'contactNo1.required' => 'Contact No should be provided!',
                'contactNo1.min' => 'Contact No should be contain 9 number!',
                'contactNo1.max' => 'Contact No should be contain 9 number!',
                'bankName.required' => 'Bank Name should be provided!',
                'bankName.max' => 'Bank Name must be less than 255 characters long.',
                'accountNo.required' => 'Account No should be provided!',
                'accountNo1.min' => 'Account No should be contain 15 number!',
                'accountNo1.max' => 'Account No should be contain 25 number!',

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            $saveSupplier = new Supplier();
            $saveSupplier->company_name = $supplierName;
            $saveSupplier->contact_no = $contactNo1;
            $saveSupplier->email = strtolower($email);
            $saveSupplier->address = $address;
            $saveSupplier->bank_name = $bankName;
            $saveSupplier->account_no = $accountNo;

            $saveSupplier->status = '1';
            $saveSupplier->save();
            DB::commit();
            return response()->json(['success' => 'Supplier saved successfully']);
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getById(Request $request)
    {
        $supplierId = $request['supplierId'];
        $getSupplier = Supplier::find($supplierId);
        return response()->json($getSupplier);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $uSupplierName = $request['uSupplierName'];
            $uContactNo1 = $request['uContactNo1'];
            $uEmail = $request['uEmail'];
            $uAddress = $request['uAddress'];
            $hiddenSupplierId = $request['hiddenSupplierId'];
            $uBankName = $request['uBankName'];
            $uAccountNo = $request['uAccountNo'];

            $validator = \Validator::make($request->all(), [

                'uSupplierName' => 'required|max:45',
                'uContactNo1' => 'required|min:9|max:9',
                'uBankName' => 'required|min:10',
                'uAccountNo' => 'required|min:15|max:25',

            ], [
                'uSupplierName.required' => 'supplier Name should be provided!',
                'uSupplierName.max' => 'supplier Name must be less than 255 characters long.',
                'uContactNo1.required' => 'Contact No should be provided!',
                'uContactNo1.min' => 'Contact No should be contain 9 number!',
                'uBankName.required' => 'Bank Name should be provided!',
                'uBankName.max' => 'Bank Name Name must be less than 255 characters long.',
                'uAccountNo.min' => 'Account No should be contain 15 number!',
                'uAccountNo.max' => 'Account No should be contain 25 number!',

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            $updateSupplier = Supplier::find($hiddenSupplierId);
            $updateSupplier->company_name = $uSupplierName;
            $updateSupplier->contact_no = $uContactNo1;
            $updateSupplier->email = strtolower($uEmail);
            $updateSupplier->address = $uAddress;
            $updateSupplier->bank_name = $uBankName;
            $updateSupplier->account_no= $uAccountNo;
                        
            $updateSupplier->update();
            DB::commit();
            return response()->json(['success' => 'Supplier updated successfully']);
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
