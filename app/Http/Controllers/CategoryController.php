<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        if($categories->count()>0){
        return response()->json([
                'categories' => $categories
        ],200);
    }else{
            return response()->json([
                'message'=>'No records found'],404);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'description' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' =>$validator->messages()
            ],404);
        }else{
                $category = Category::create([
                'name' => $request->name,
                'description' => $request->description,

            ]);
            if($category){
                return response()->json([
                    'message'=> "Category created successfully"
                ],200);
            }else{
                return response()->json([
                    'message'=> "Something went wrong "
                ],404);
            }
            }
        }
    public function show($id)
    {
        $category =Category::find($id);
        if($category){
            return response()->json([
                'message'=> $category
            ],200);
        }else{
            return response()->json([
                'message'=> "No such category found"
            ],404);
        }
    }
    public function searchByName(Request $request)
    {
    $category = Category::with('products')->where('name', $request->name)->first();
        if (!$category) {
            return response()->json([
                'message' => 'Category not found.',
            ], 404);
        }
        return response()->json($category);
    }

    
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'description' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' =>$validator->messages()
            ],404);
        }else{
            $category = Category::find($id);

            if($category){

                    $category->update([
                    'name' => $request->name,
                    'description' => $request->description
                ]);

                return response()->json([
                    'message'=> "Category updated successfully"
                ],200);
            }else{
                return response()->json([
                    'message'=> "No such category found "
                ],404);
            }
            }
        
}
        public function destroy($id)
        {
            $category =Category::find($id);
            if($category){
                $category->delete();
                return response()->json([
                    'message'=> "Category deleted successfully"
                ],200);
            }else{
                return response()->json([
                    'message'=> "No such category found"
                ],404);
            }
        }
        public function getProductsByCategory($id)
        {
            $category = Category::with('products')->find($id);
            if ($category) {
                return response()->json([
                    'category' => $category
                ], 200);
            } else {
                return response()->json([
                    'message' => "No such category found"
                ],404);
            }
        }
}