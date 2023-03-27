<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $OrderItem = OrderItem::all();
        return response()->json([
            'OrderItem'=>$OrderItem
        ],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|integer',
            'product_id'=> 'required|integer',
            'quantity'=> 'required|integer'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' =>$validator->messages()
            ],404);
        }else{
            $OrderItem = OrderItem::create([
                'order_id' => $request->order_id,
                'product_id'=> $request->product_id,
                'quantity' => $request ->quantity
            ]);
            return response()->json([
                'message' => 'OrderItem successfully created',
                'data' => $OrderItem
            ], 200);
        }
    }
    
    public function show($id)
    {
        $OrderItem = OrderItem::find($id);
        if($OrderItem){
        return response()->json([
            'OrderItem' => $OrderItem
        ],200);}
    }
    
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'order_id' => 'required|integer',
            'product_id'=> 'required|integer',
            'quantity'=> 'required|integer'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' =>$validator->messages()
            ],404);
        }else{
            $OrderItem = OrderItem::find($id);
            if($OrderItem){
                $OrderItem->update([
                    'order_id' => $request->order_id,
                    'product_id'=> $request->product_id,
                    'quantity' => $request ->quantity
                ]);
                return response()->json([
                    'message'=> "OrderItem updated successfully"
                ],200);
            }  
        }
    }
    
    public function destroy($id)
    {
        $OrderItem = OrderItem::find($id);
        if($OrderItem){
        $OrderItem->delete();
        return respone()->json([
            'message' => "OrderItem deleted successfully"
        ],200);}
    }
}
