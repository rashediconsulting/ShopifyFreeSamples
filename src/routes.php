<?php

Route::group(["prefix" => "sfs"], function(){
    Route::post('/cart_update', [\RashediConsulting\ShopifyFreeSamples\Http\Controllers\ShopifyFreeSamplesController::class, "updatedCheckout"]);
    Route::post('/product_update', [\RashediConsulting\ShopifyFreeSamples\Http\Controllers\ShopifyFreeSamplesController::class, "updatedProduct"]);
});
