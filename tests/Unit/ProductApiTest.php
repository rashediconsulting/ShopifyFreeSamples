<?php

namespace RashediConsulting\ShopifyFreeSamples\Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;


use RashediConsulting\ShopifyFreeSamples\Services\ApiService;

class ProductApiTest extends TestCase
{

    public $api_service;

    public function setUp(): void {

        parent::setUp();

        $this->api_service = app(ApiService::class);
    }

    /**
     * A basic test example.
     */
    public function test_can_connect_to_api(): void
    {
        $result = $this->api_service->getApiConnection();
        $this->assertNotNull($result);
    }

    public function test_can_obtain_products(): void
    {
        $api_connection = $this->api_service->getApiConnection();

        $all_products = $this->api_service->getAllProducts();

        $this->assertIsArray($all_products);
        $this->assertGreaterThan(1, count($all_products));
    }

    public function test_can_store_products_on_cache(): void
    {
       $api_connection = $this->api_service->getApiConnection();

        $all_products = $this->api_service->getAllProducts();

        Cache::put("ShopifyFreeSamples.product_list", $this->api_service->getAllProducts());

        $cache_prd = collect(Cache::get("ShopifyFreeSamples.product_list"));
        $this->assertIsArray($all_products);
        $this->assertGreaterThan(1, count($all_products));
    }

}
