<?php

Route::group(["prefix" => "sfs"], function(){
    Route::post('/cart_update', [\RashediConsulting\ShopifyFreeSamples\Http\Controllers\ShopifyFreeSamplesController::class, "updatedCheckout"]);
    Route::post('/product_update', [\RashediConsulting\ShopifyFreeSamples\Http\Controllers\ShopifyFreeSamplesController::class, "updatedProduct"]);

    Route::get('/free-samples', 'RashediConsulting\ShopifyFreeSamples\Http\Controllers\ShopifyFreeSamplesController@freeSamples')->name('free-samples');
    Route::get('/free-samples/sample-set/{sfs_set}', 'RashediConsulting\ShopifyFreeSamples\Http\Controllers\ShopifyFreeSamplesController@sampleSetDetail')->name('free-sample-set-detail');
});
