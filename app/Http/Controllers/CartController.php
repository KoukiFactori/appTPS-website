<?php

namespace App\Http\Controllers;

use App\Models\Bde\Cart;
use App\Models\Bde\Order;
use App\Models\Bde\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::all();
        $carts->load('orders');

        return [
            'data' => $carts
        ];
    }

    public function show(string $id)
    {
        $cart = Cart::where('id', $id)->first();

        return [
            'data' => $cart,
        ];
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $payload = $request->json()->all();

        $cart = Cart::create();

        foreach ($payload as $order) {
            $product = Product::where('id', $order['product_id'])->first();
            $price = $order['price'];
            $amount = $order['amount'];

            Order::create(
                [
                    'amount' => $amount,
                    'product_id' => $product->id,

                    'member_id' => $user->bde_id,
                    'cart_id' => $cart->id,

                    // This field SHOULD NOT be specified by user but it's ok for now
                    'price' => $price,
                ]
            );
        }

        return [
            "data" => $cart
        ];
    }

    public function delete(): Response
    {
        return response('Not Implemented', 501);
    }
}
