<?php

namespace RashediConsulting\ShopifyFreeSamples\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

use RashediConsulting\ShopifyFreeSamples\Services\ApiService;

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

    public $api_service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ApiService $api_service)
    {
        $this->api_service = $api_service;
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Cache::put("ShopifyFreeSamples.product_list", $this->api_service->getAllProducts());
        return 0;
    }
}
