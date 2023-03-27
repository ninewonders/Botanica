<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;

use App\Http\Requests\ProductStoreRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        if($products->count()>0){
        return response()->json([
                'status' => 200,
                'products' => $products
        ],200);
    }else{
            return response()->json([
                'status'=>404,
                'message'=>'No records found'],404);
        }
    }
        public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'category_id' => 'required|integer'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'error' => $validator->messages()
        ], 422);
    }

    // Generate a unique name for the image
    $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();

    $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'image' => $imageName,
        'category_id' => $request->category_id
    ]);

    // Save the image to the disk
    Storage::disk('public')->put($imageName, file_get_contents($request->image));

    return response()->json([
        'status' => 200,
        'message' => 'Product successfully created',
        'data' => $product
    ], 200);
}

    public function show($id)
    {
        $Product =Product::find($id);
        if($Product){
            return response()->json([
                'status'=>200,
                'message'=> $Product
            ],200);
        }else{
            return response()->json([
                'status'=>500,
                'message'=> "No such Product found"
            ],404);
        }
    }
    
    public function searchByName(Request $request)
    {
    $product = Product::with('category')->where('name', $request->name)->first();
        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }
        return response()->json($product);
    }
    public function update(Request $request, int $id)
    {
    //     $validator = Validator::make($request->all(),[
    //         'name' => 'required|string',
    //         'description' => 'required|string',
    //         'price' => 'required|numeric',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'category_id' => 'required|integer'
    //     ]);
    //     if($validator->fails()){
    //         return response()->json([
    //             'status' => 422,
    //             'error' =>$validator->messages()
    //         ],422);
    //     }else{
    //         $Product = Product::find($id);

    //         if($Product){

    //                 $Product->update([
    //                 'name' => $request->name,
    //                 'description' => $request->description
    //             ]);

    //             return response()->json([
    //                 'status'=>200,
    //                 'message'=> "Product updated successfully"
    //             ],200);
    //         }else{
    //             return response()->json([
    //                 'status'=>404,
    //                 'message'=> "No such Product found "
    //             ],404);
    //         }
    //         }
        
}
        public function destroy($id)
        {
            $Product =Product::find($id);
            if($Product){
                $Product->delete();
                return response()->json([
                    'status'=>200,
                    'message'=> "Product deleted successfully"
                ],200);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=> "No such Product found"
                ],404);
            }
        }
}