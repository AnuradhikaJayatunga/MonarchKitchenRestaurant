<?php


namespace App\Http\Controllers;


use App\Category;
use App\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function productsIndex()
    {

        $productViews = Product::all();
        $categories = Category::where('status', 1)->get();

        return view('product.products', ['title' => 'Products Inventory', 'categories' => $categories, 'productViews' => $productViews]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $pName = $request['pName'];
            $category = $request['category'];
            $buyingPrice = $request['buyingPrice'];
            $descption = $request['description'];


            $validator = \Validator::make($request->all(), [

                'pName' => 'required|max:45',
                'category' => 'required',
                'buyingPrice' => 'required|not_in:0'
            ], [
                'category.required' => 'Category should be provided!',
                'pName.required' => 'Product Name should be provided!',
                'pName.max' => 'Product Name must be less than 45 characters long.',
                'buyingPrice.required' => 'buying Price should be provided!',
                'buyingPrice.not_in' => 'buying Price may not be 0!',
            ]);

            if (Product::where('product_name', $pName)->where('category_idcategory', $category)->first()) {
                return response()->json(['errors' => ['error' => 'Product Name already exist.']]);
            }


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $saveProduct = new Product();
            $saveProduct->category_idcategory = $category;
            $saveProduct->product_name = $pName;
            $saveProduct->buying_price = $buyingPrice;
            $saveProduct->description = $descption;
            $saveProduct->status = '1';
            $saveProduct->save();
            DB::commit();
            return response()->json(['success' => 'Extra Item saved successfully.']);
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getById(Request $request)
    {
        $productId = $request['productId'];

        if ($productId != null) {
            $getPrice = Product::find($productId);
            return response()->json($getPrice);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $hiddenUItemId = $request['hiddenUItemId'];
            $uPName = $request['uPName'];
            $uCategory = $request['uCategory'];
            $uDescription = $request['uDescription'];
            $uBuyingPrice = $request['uBuyingPrice'];

            $validator = \Validator::make($request->all(), [

                'uCategory' => 'required',
                'uPName' => 'required|max:45',
                'uBuyingPrice' => 'required|not_in:0',
            ], [
                'uCategory.required' => 'category should be provided!',
                'uPName.required' => 'Product Name should be provided!',
                'uPName.max' => 'Product Name must be less than 45 characters long.',
                'uBuyingPrice.required' => 'Buying Price should be provided!',
                'uBuyingPrice.not_in' => 'Buying Price may not be 0!',
            ]);

            if (Product::where('product_name', $uPName)->where('idproduct', '!=', $hiddenUItemId)
                ->where('category_idcategory', $uCategory)->first()
            ) {
                return response()->json(['errors' => ['error' => 'Product Name already exist.']]);
            }
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $updateProduct = Product::find($hiddenUItemId);
            $updateProduct->category_idcategory = $uCategory;
            $updateProduct->product_name = $uPName;
            $updateProduct->description = $uDescription;
            $updateProduct->buying_price = $uBuyingPrice;
            $updateProduct->update();
            DB::commit();
            return response()->json(['success' => 'Extra Item updated successfully.']);
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
