<?php


namespace App\Http\Controllers;

use App\Package;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function packagesIndex()
    {
        $packages = Package::get();

        return view('packages.packages', ['title' => 'Packages', 'packages' => $packages]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            $name = $request['name'];

            $validator = \Validator::make($request->all(), [

                'name' => 'required|max:45',
            ], [
                'name.required' => 'Name should be provided!',
                'name.max' => 'Name must be less than 255 characters long.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            $saveSupplier = new Package();
            $saveSupplier->package = $name;
            $saveSupplier->status = '1';
            $saveSupplier->save();
            DB::commit();
            return response()->json(['success' => 'Package saved successfully']);
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getById(Request $request)
    {
        $id = $request['id'];
        $getSupplier = Package::find($id);
        return response()->json($getSupplier);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $name = $request['uName'];
            $hiddenPackageId = $request['hiddenPackageId'];

            $validator = \Validator::make($request->all(), [

                'uName' => 'required|max:45',
            ], [
                'uName.required' => 'Name should be provided!',
                'uName.max' => 'Name must be less than 255 characters long.'

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            $updateSupplier = Package::find($hiddenPackageId);
            $updateSupplier->package = $name;
            $updateSupplier->update();
            DB::commit();
            return response()->json(['success' => 'Package updated successfully']);
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
