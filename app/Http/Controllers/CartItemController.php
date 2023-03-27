<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        $CartItem = CartItem::all();
        return response()->json([
            'cartItem'=>$CartItem
        ],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'cart_id' => 'required|integer',
            'product_id'=> 'required|integer',
            'quantity'=> 'required|integer'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' =>$validator->messages()
            ],404);
        }else{
            $cartItem = CartItem::create([
                'cart_id' => $request->cart_id,
                'product_id'=> $request->product_id,
                'quantity' => $request ->quantity
            ]);
            return response()->json([
                'message' => 'Cartitem successfully created',
                'data' => $cartItem
            ], 200);
        }
    }
    
    public function show($id)
    {
        $cartItem = CartItem::find($id);
        if($cartItem){
        return response()->json([
            'CartItem' => $cartItem
        ],200);}
    }
    
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'cart_id' => 'required|integer',
            'product_id'=> 'required|integer',
            'quantity'=> 'required|integer'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' =>$validator->messages()
            ],404);
        }else{
            $cartItem = CartItem::find($id);
            if($cartItem){
                $cartItem->update([
                    'cart_id' => $request->cart_id,
                    'product_id'=> $request->product_id,
                    'quantity' => $request ->quantity
                ]);
                return response()->json([
                    'message'=> "CartItem updated successfully"
                ],200);
            }  
        }
    }
    
    public function destroy($id)
    {
        $cartItem = CartItem::find($id);
        if($cartItem){
        $cartItem->delete();
        return respone()->json([
            'message' => "CartItem deleted successfully"
        ],200);}
    }
}
