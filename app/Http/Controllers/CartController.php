<?php

namespace App\Http\Controllers;

use App\Models\Bde\Cart;
use App\Models\Bde\Member;
use App\Models\Bde\Order;
use App\Models\Bde\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::all()
            ->load('orders');

        return [
            'data' => $carts
        ];
    }

    public function show(Cart $cart)
    {
        return [
            'data' => $cart,
        ];
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $payload = array_map(
            function ($line)
            {
                return [
                    'product' => Product::where('id', $line['product_id'])->first(),
                    'amount' => $line['amount'], // Unchanged
                ];
            },
            $request->all()
        );
        
        $cart = Cart::create(["member_id" => $user->bde_id]);
        
        foreach ($payload as $line)
        {
            $cart->price += $line['amount'] * $line['product']->price; // Compute final price as we insert the order item
            
            Order::create([
                'product_id' => $line['product']->id,
                'amount' => $line['amount'],
                'price' => $line['amount'] * $line['product']->price,
                'member_id' => $user->bde_id,
                'cart_id' => $cart->id
            ]);
        }

        return [
            "data" => $cart
        ];
    }

    public function delete(Cart $cart): Response
    {
        $cart->delete();
        return \response(status: 204);
    }

    public function checkout(Cart $cart) {
        $user = $cart->client;        

        $cart->status = "payed";
        $user->balance += $cart->price; // Cart has a negative price

        $user->save();
        $cart->save();
    }
}
