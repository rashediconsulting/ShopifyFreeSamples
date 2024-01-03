<?php

Route::group(["prefix" => "sfs"], function(){
    Route::post('/cart_update', [\RashediConsulting\ShopifyFreeSamples\App\Http\Controllers\ShopifyFreeSamplesController::class, "updatedCheckout"]);
    Route::post('/product_update', [\RashediConsulting\ShopifyFreeSamples\App\Http\Controllers\ShopifyFreeSamplesController::class, "updatedProduct"]);
});
