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
                'id' => 'Z2NwLXVzLWNlbnRyYWwxOjAxSE1FMVMwRkI1Qk1GUVdaVlFSNThNMk5O',
                'token' => 'Z2NwLXVzLWNlbnRyYWwxOjAxSE1FMVMwRkI1Qk1GUVdaVlFSNThNMk5O',
                'line_items' => [
                    [
                        'id' => 45793676787994,
                        'properties' => [],
                        'quantity' => 1,
                        'variant_id' => 45793676787994,
                        'key' => '45793676787994:2ca46459da95ed7c86e3e3d1166237e3',
                        'discounted_price' => 0.00,
                        'discounts' => [],
                        'gift_card' => null,
                        'grams' => 0,
                        'line_price' => 0.00,
                        'original_line_price' => 0.00,
                        'original_price' => 0.00,
                        'price' => 0.00,
                        'product_id' => 8454768460058,
                        'sku' => 'day-sample',
                        'taxable' => 1,
                        'title' => 'DAY SAMPLE',
                        'total_discount' => 0.00,
                        'vendor' => 'DoctorMiRc',
                        'discounted_price_set' => [
                            'shop_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                        ],
                        'line_price_set' => [
                            'shop_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                        ],
                        'original_line_price_set' => [
                            'shop_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                        ],
                        'price_set' => [
                            'shop_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                        ],
                        'total_discount_set' => [
                            'shop_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                        ],
                    ],
                    [
                        'id' => 45226876764442,
                        'properties' => [],
                        'quantity' => 6,
                        'variant_id' => 45226876764442,
                        'key' => '45226876764442:0f2cae868f60d372eb8634d6c61945e1',
                        'discounted_price' => 65.00,
                        'discounts' => [],
                        'gift_card' => null,
                        'grams' => 215,
                        'line_price' => 390.00,
                        'original_line_price' => 390.00,
                        'original_price' => 65.00,
                        'price' => 65.00,
                        'product_id' => 8312604393754,
                        'sku' => 'clean',
                        'taxable' => 1,
                        'title' => 'CLEAN - 150ml',
                        'total_discount' => 0.00,
                        'vendor' => 'DOCTOR Mi!',
                        'discounted_price_set' => [
                            'shop_money' => ['amount' => 65.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 65.0, 'currency_code' => 'EUR'],
                        ],
                        'line_price_set' => [
                            'shop_money' => ['amount' => 390.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 390.0, 'currency_code' => 'EUR'],
                        ],
                        'original_line_price_set' => [
                            'shop_money' => ['amount' => 390.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 390.0, 'currency_code' => 'EUR'],
                        ],
                        'price_set' => [
                            'shop_money' => ['amount' => 65.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 65.0, 'currency_code' => 'EUR'],
                        ],
                        'total_discount_set' => [
                            'shop_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                            'presentment_money' => ['amount' => 0.0, 'currency_code' => 'EUR'],
                        ],
                    ],
                ],
                'note' => '',
                'updated_at' => '2024-01-18T10:54:40.249Z',
                'created_at' => '2024-01-18T10:27:27.239Z',
            ]
        \Log::info(print_r($request->all(), true));
        */

        $data = $request->all();


        if(isset($data["items"])){
            $result = $this->cart_service->addSamplesToCart($data);
            return response()->json($result);
        }else{
            return response()->json(["success"=>true, "message"=>$data]);
        }
    }
}
