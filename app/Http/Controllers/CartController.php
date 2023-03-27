<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $cart = Cart::with('cartItems.product')->where('user_id', $user->id)->first();
    if ($cart) {
        return response()->json([
            'cart' => $cart,
        ], 200);
    } else {
        return response()->json([
            'message' => "No such cart found"
        ],404);
    }
}
    public function store(Request $request)
    {
        $user = auth::user();
        $cart = Cart::create([
            'user_id' => $user->id
        ]);
        if ($cart) {
            return response()->json([
                'message' => 'Cart created successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to create cart'
            ], 404);
        }
    }
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|integer'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 404,
                'error' =>$validator->messages()
            ],404);
        }else{
            $cart = Cart::find($id);
        if($cart){
            $cart = Cart::update([
                'user_id' => $request->user_id
            ]);
            return response()->json([
                'message'=> "Cart updated successfully"
            ],200);
            }  
        }
    }
    public function destroy()
    {
        $user = Auth::user();
        $cart = Cart::with('cartItems')->where('user_id', $user->id)->first();
        if($cart){
            $cart->delete();
        return response()->json([
            'status' => 200,
            'message' => "cart deleted successfully"
        ],200);
        }
    }
    function calculateCartTotals() 
    {
        $user = Auth::user();
        $cart = Cart::with('cartItems.product')->where('user_id', $user->id)->first();
        $cartTotal = 0;
        foreach ($cart->cartItems as $cartItem) {
            $cartItem->total = $cartItem->quantity * $cartItem->product->price;
            $cartTotal += $cartItem->total;
        }
    return response()->json([
        'total'=> $cartTotal
    ],200);
    }
    public function addToCart(Request $request)
    {
        $user = Auth::user();
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
    
        $cart = Cart::with('cartItems')->where('user_id', $user->id)->first();
    
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user->id,
            ]);
        }
    
        $cartItem = $cart->cartItems()->where('product_id', $request->product_id)->first();
    
        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
            return response()->json(['message' => "Product quantity updated"], 200);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
            return response()->json(['message' => 'Product added to cart.'], 200);
        }
    }
    
    public function minusQuantity(Request $request)
    {
        $user = Auth::user();
        $product = Product::find($request->product_id);     
        $cart = Cart::with('cartItems')->where('user_id', $user->id)->first();
        $cartItem = $cart->cartItems()->where('product_id', $request->product_id)->first();
            $cartItem->quantity -= $request->quantity;
            $cartItem->save();
            return response()->json(['message' => "Product quantity updated"], 200);
    }
    public function addQuantity(Request $request)
    {
        $user = Auth::user();        
        $cart = Cart::with('cartItems')->where('user_id', $user->id)->first();
        $cartItem = $cart->cartItems()->where('product_id', $request->product_id)->first();
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
            return response()->json(['message' => "Product quantity updated"], 200);
    }
    public function deleteCartItem(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with('cartItems')->where('user_id', $user->id)->first();
        $cartItem = $cart->cartItems()->where('product_id', $request->product_id)->first();
            $cartItem->delete();
            $cart->save();
            return response()->json(['message' => "Cart Item is removed"], 200);
    }
    
    public function show($id)
    {
        $cart = Cart::find($id);
        if($cart){
        return response()->json([
            'status' => 200,
            'cart' => $cart
        ],200);
        }
    }
}
