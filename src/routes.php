<?php

Route::group(["prefix" => "sfs"], function(){
    Route::get('/cart_update', [\RashediConsulting\ShopifyFreeSamples\App\Http\Controllers\ShopifyFreeSamplesController::class, "updatedProduct"]);
    Route::get('/product_update', [\RashediConsulting\ShopifyFreeSamples\App\Http\Controllers\ShopifyFreeSamplesController::class, "updatedCheckout"]);
});
