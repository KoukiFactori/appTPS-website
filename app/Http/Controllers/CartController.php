<?php

namespace App\Http\Controllers;

use App\Models\Bde\Cart;
use App\Models\Bde\Order;
use App\Models\Bde\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function index(): Response
    {
        return Cart::all()
            ->map(
                function (Cart $cart) {
                    return [
                        'date' => $cart->date,
                        'price' => $cart->price,
                        'state' => $cart->state,
                        'orders' => $cart->orders()
                    ];
                }
            );
    }

    public function show(string $id): Response
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

                    'date' => $current_date,

                    // This field SHOULD NOT be specified by user but it's ok for now
                    'total_price' => $price,
                ]
            );
        }
    }

    public function delete(): Response
    {
        return response('Not Implemented', 501);
    }

    public function validate(string $id)
    {

        return response('Not Implemented', 501);
    }
}
