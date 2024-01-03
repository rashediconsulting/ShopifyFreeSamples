<?php

namespace RashediConsulting\ShopifyFreeSamples\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class ShopifyFreeSamplesController extends Controller
{

    public function updatedProduct()
    {
        Artisan::call('ShopifyFreeSamples:update-product-cache');
        return response()->json(["success" => true]);
    }

    public function updatedCheckout(Request $request)
    {
        \Log::info(print_r($request->all(), true));

        //CartService::addSamplesToCart();
    }
}
