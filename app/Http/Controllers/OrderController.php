<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Http\Controllers\CartController;



       
class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $Order = Order::with('orderItems.product')->where('user_id', $user->id)->get();
        if($Order->count()>0){
        return response()->json([
                'Order'=>$Order
            ],200);
        }else{
            return response()->json([
                'message'=>'No records found'
            ],404);
        }
    }
    public function allOrders()
    {
        $Order = Order::with('orderItems.product')->get();

        if($Order->count()>0){
        return response()->json([
                'Order'=>$Order
            ],200);
        }else{
            return response()->json([
                'message'=>'No records found'
            ],404);
        }
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with('cartItems.product')->where('user_id', $user->id)->first();
        $total=app('App\Http\Controllers\CartController')->calculateCartTotals()->original;
        $Order =Order::create([
                "user_id"=> $user->id,
                "address"=>$request->address,
                "phone_number"=>$request->phone_number,
                "total_price"=>$total['total'],
            ]);
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        foreach ($cartItems as $cartItem) {
             $orderItem = OrderItem::create([
                'order_id' => $Order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
              ]);
         }
         app('App\Http\Controllers\CartController')->destroy();

         return response()->json([
             'message' => 'Order created successfully and the cart has been deleted',
             'order' => $Order,
         ],200);

        }

        public function updateStatus(Request $request)
        {
            $order = Order::find($request->id);
            if (!$order) {
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }
            $order->status = $request->status;
            $order->save();
        
            return response()->json([
                'message' => 'Order status updated successfully',
                'order' => $order
            ], 200);
        }
        
    public function show($id)
    {
        $Order = Order::find($id);
        if($Order){
            return response()->json([
                'order'=>$Order
            ],200);
        }else{
            return response()->json([
                'message'=>'no such order found'
            ],404);
        }
    }

    public function update(Request $request, int $id)
    {
           $validator = Validator::make($request->all(),[
            'user_id'=>"required|integer",
            'address'=>"required|string",
            'phone_number'=>'required|integer',
            'total_price'=>"required|integer",
            'status'=>"required|string",
        ]);
        if($validator->fails()){
            return response()->json([
                'error' =>$validator->messages()
            ],404);
        }else{
            $Order=Order::find($id);
            if($Order){
            $Order->update([
                "user_id"=> $request->user_id,
                "address"=>$request->address,
                "phone_number"=>$request->phone_number,
                "total_price"=>$request->total_price,
                "status"=>$request->status,
            ]);
            
            return response()->json([
                'message'=> "Order updated successfully"
            ],200);
        }else{
            return response()->json([
                'message'=> "No such order found "
            ],404);
        }
        }
    }

    public function destroy()
    {   
        $user = Auth::user();
        $Order = Order::with('orderItems')->where('user_id', $user->id)->first();
        if($Order){
            $Order->delete();
            return response()->json([
                'message'=>'Order deleted successfully'
            ],200);
        }else{
            return response()->json([
                'message'=>'no such order found'
            ],404);
        }
    }
  
}
