<?php

namespace RashediConsulting\ShopifyFreeSamples\App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller;

class ShopifyFreeSamplesController extends Controller
{

    public function updatedProduct()
    {
        Artisan::call('ShopifyFreeSamples:update-product-cache');
    }

    public function updatedCheckout()
    {

    }
}
