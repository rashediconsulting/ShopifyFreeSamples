<?php

namespace RashediConsulting\ShopifyFreeSamples\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateProductCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ShopifyFreeSamples:update-product-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the cache of shopify products';

    public $order_service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shopify = \Signifly\Shopify\Factory::fromArray([
            'access_token' => config('shopifyfreesamples.shopify_store_token', ''),
            'domain'=> config('shopifyfreesamples.shopify_domain', ''),
            'api_version'=> config('shopifyfreesamples.shopify_api_version', '2021-01'),
        ]);

        $pages = $shopify->paginateProducts(['limit' => 100]); // returns Cursor

        $tmp_products = collect();

        foreach ($pages as $page) {
            // $page is a Collection of ApiResources
            $tmp_products = $tmp_products->merge($page);
        }

        $product_list = [];
        foreach ($tmp_products as $t_prd) {
            $product_list[] = $t_prd->toArray();
        }

        Cache::put("ShopifyFreeSamples.product_list", $product_list);
        return 0;
    }
}
