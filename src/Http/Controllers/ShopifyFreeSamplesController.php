<?php

namespace RashediConsulting\ShopifyFreeSamples\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use RashediConsulting\ShopifyFreeSamples\Services\CartService;

class ShopifyFreeSamplesController extends Controller
{
    public $cart_service;

    function __construct(CartService $cart_service) {

        $this->cart_service = $cart_service;
    }

    public function updatedProduct()
    {
        Artisan::call('ShopifyFreeSamples:update-product-cache');
        return response()->json(["success" => true]);
    }

    public function updatedCheckout(Request $request)
    {
        /*
            Example of the recieved data:
            $request->all() = [
            'id' => 'eeafa272cebfd4b22385bc4b645e762c',
            'token' => 'eeafa272cebfd4b22385bc4b645e762c',
            'line_items' => [
                [
                    'id' => 704912205188288575,
                    'properties' => [],
                    'quantity' => 3,
                    'variant_id' => 704912205188288575,
                    'key' => '704912205188288575:33f11f7a1ec7d93b826de33bb54de37b',
                    'discounted_price' => 19.99,
                    'discounts' => [],
                    'gift_card' => null,
                    'grams' => 200,
                    'line_price' => 59.97,
                    'original_line_price' => 59.97,
                    'original_price' => 19.99,
                    'price' => 19.99,
                    'product_id' => 788032119674292922,
                    'sku' => 'example-shirt-s',
                    'taxable' => 1,
                    'title' => 'Example T-Shirt',
                    'total_discount' => 0.00,
                    'vendor' => 'Acme',
                    'discounted_price_set' => [
                        'shop_money' => ['amount' => 19.99, 'currency_code' => 'EUR'],
                        'presentment_money' => ['amount' => 19.99, 'currency_code' => 'EUR'],
                    ],
                    'line_price_set' => [
                        'shop_money' => ['amount' => 59.97, 'currency_code' => 'EUR'],
                        'presentment_money' => ['amount' => 59.97, 'currency_code' => 'EUR'],
                    ],
                    'original_line_price_set' => [
                        'shop_money' => ['amount' => 59.97, 'currency_code' => 'EUR'],
                        'presentment_money' => ['amount' => 59.97, 'currency_code' => 'EUR'],
                    ],
                    'price_set' => [
                        'shop_money' => ['amount' => 19.99, 'currency_code' => 'EUR'],
                        'presentment_money' => ['amount' => 19.99, 'currency_code' => 'EUR'],
                    ],
                    'total_discount_set' => [
                        'shop_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                        'presentment_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                    ],
                ],
            ],
            'note' => null,
            'updated_at' => '2024-01-03T15:39:57.258Z',
            'created_at' => '2024-01-03T15:39:57.258Z',
        ]
        \Log::info(print_r($request->all(), true));
        */

        $data = $request->all();

        $result = $this->cart_service->addSamplesToCart($data);

        return response()->json($result);

    }
}
