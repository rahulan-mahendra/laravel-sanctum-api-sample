<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {

        $limit = $request->limit ? $request->limit : 20;

        $products = Product::select("*");

        $searchFilter = $request->search ? $request->search : '';

        // filters options
        $departmentFilter =  $request->department ? $request->department : '';

         // filters options conditions
        if(isset($departmentFilter) && $departmentFilter != "") {
            $products->where('department_id', '=', $departmentFilter);
        }

        if (!empty($searchFilter) and !is_null($searchFilter)) {
            $products = $products->where(function ($query) use($searchFilter) {
                $query->where('name', 'LIKE', '%'.$searchFilter.'%')
                    ->orWhere('description', 'LIKE', '%'.$searchFilter.'%');
            });
        }
        $products = $products->orderBy('id','DESC')->paginate($limit);

        return response()->json([
            "success" => true,
            "message" => "Operation successful.",
            "data" => $products,
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        $product = Product::where('id',$id)->first();
        if (is_null($product)) {
            return  response()->json([
                "success" => false,
                "message" => "Resource not found.",
                "data" => [],
            ],404);
        }
        return response()->json([
            "success" => true,
            "message" => "Operation successful.",
            "data" => $product,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->save();
            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Operation successful.",
                "data" => $product,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "success" => false,
                "message" => "Operation failed.",
                "error" => $e->getMessage()
            ],417);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = null;
        try {
            $product = Product::where('id',$id)->firstOrFail();
        } catch(\Exception $exception){
            DB::rollback();
            $errormsg = 'Resource not found';
            return response()->json([
                "success" => false,
                "message" => $errormsg,
            ],404);
        }

        DB::beginTransaction();
        try {
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Operation successful",
                "data" => $product
            ],200);

        } catch(\Exception $exception){
            DB::rollback();
            $errormsg = 'Operation failed';
            return response()->json([
                "success" => false,
                "message" => $errormsg,
                "error" => $exception->getMessage(),
            ],417);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::where('id',$id)->firstOrFail();
        } catch(\Exception $exception){
            $errormsg = 'Resource not found';
            return response()->json([
                "success" => false,
                "message" => $errormsg,
            ],404);
        }

        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Operation successful",
                "data" => []
            ],200);

        } catch(\Exception $exception){
            DB::rollback();
            $errormsg = 'Operation failed';
            return response()->json([
                "success" => false,
                "message" => $errormsg,
            ],417);
        }
    }
}
