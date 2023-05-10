<?php


namespace App\Http\Controllers;


use App\Category;
use App\Customer;
use App\MainCategory;
use App\Measurement;
use Exception;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function categoriesIndex(Request $request)
    {

        $Categories = Category::all();

        return view('category.categories', ['title' => 'Categories', 'categories' => $Categories]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $category = $request['category'];
            $validator = \Validator::make($request->all(), [
                'category' => 'required|max:20',
            ], [
                'category.required' => 'Category should be provided!',
                'category.max' => 'Category must be less than 20 characters long.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
            $categoryExist = Category::where('category_name', $category)->first();

            if ($categoryExist != null) {

                return \response()->json(['errors' => ['a' => 'Category already exist.']]);
            }

            $save = new Category();
            $save->category_name = $category;
            $save->status = '1';
            $save->save();
            DB::commit();
            return \response()->json(['success' => 'Category saved successfully']);
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update(Request $request)
    {

        $uCategory = $request['uCategory'];
        $hiddenCatId = $request['hiddenCatId'];
        $validator = \Validator::make($request->all(), [

            'uCategory' => 'required|max:20',
        ], [
            'uCategory.required' => 'Category should be provided!',
            'uCategory.max' => 'Category must be less than 20 characters long.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $categoryExist = Category::where('category_name', strtoupper($uCategory))->where('idcategory', '!=', $hiddenCatId)->first();

        if ($categoryExist != null) {

            return \response()->json(['errors' => ['a' => 'Category already exist.']]);
        }

        $update = Category::find($hiddenCatId);
        $update->category_name = $uCategory;
        $update->update();

        return \response()->json(['success' => 'Category updated successfully']);
    }

    public function getById(Request $request)
    {
        $categoryId = $request['categoryId'];
        return Category::find($categoryId);
    }
}
