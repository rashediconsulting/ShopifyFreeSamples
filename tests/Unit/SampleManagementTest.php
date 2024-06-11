<?php

namespace RashediConsulting\ShopifyFreeSamples\Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RashediConsulting\ShopifyFreeSamples\Models\SFSProduct;
use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;
use RashediConsulting\ShopifyFreeSamples\Models\SFSRule;
use RashediConsulting\ShopifyFreeSamples\Services\CartService;

class SampleManagementTest extends TestCase
{

    use RefreshDatabase;
    public $cart_service;

    public function setUp(): void {

        parent::setUp();

        $this->cart_service = app(CartService::class);
    }
    /**
     * A basic test example.
     */
   /*public function test_without_samples_doesnt_add(): void
   {
    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    $result = $this->cart_service->manageCartSamples([
        "total_price"=>50,
        'items' => [
            [ 'id' => 10 ],
            [ 'id' => 11 ],
            [ 'id' => 12 ],
        ]
        ]);

        dd($result);
    $this-> assertFalse($set->passes(["total_price" => 0]));
   }*/

   public function test_with_less_samples_than_quantity(): void
   {
    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1000,
        "always_added" => 0
    ]);

    $result = $this->cart_service->manageCartSamples([
        "total_price"=>50,
        'items' => [
            [ 'id' => 10 ],
            [ 'id' => 11 ],
            [ 'id' => 12 ],
        ]
    ]);

    $this-> assertCount(0,$result['samples_to_remove']);
    $this-> assertCount(2,$result['samples_to_add']);
   }

   public function test_with_equals_samples_and_quantity(): void
   {
    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1000,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1001,
        "always_added" => 0
    ]);

    $result = $this->cart_service->manageCartSamples([
        "total_price"=>50,
        'items' => [
            [ 'id' => 10 ],
            [ 'id' => 11 ],
            [ 'id' => 12 ],
        ]
        ]);

        $this-> assertCount(0, $result['samples_to_remove']);
        $this-> assertCount(2, $result['samples_to_add']);
        $this-> assertContains(1000, $result['samples_to_add']);
        $this-> assertContains(1001, $result['samples_to_add']);

   }

   public function test_two_samples_with_rule_pass(): void
   {
    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    SFSRule::Create([
        'sfs_set_id'=>$set->id,
        'type' => 'price',
        'lower_range' => 1,
        'upper_range' => 50000
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1000,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1001,
        "always_added" => 0
    ]);

    $result = $this->cart_service->manageCartSamples([
        "total_price"=>20,
        'items' => [
            [ 'id' => 10 ],
            [ 'id' => 11 ],
            [ 'id' => 12 ],
        ]
        ]);

        $this-> assertCount(0, $result['samples_to_remove']);
        $this-> assertCount(2, $result['samples_to_add']);
        $this-> assertContains(1000, $result['samples_to_add']);
        $this-> assertContains(1001, $result['samples_to_add']);

   }

   public function test_two_samples_with_rule_doesnt_pass(): void
   {
    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    SFSRule::Create([
        'sfs_set_id'=>$set->id,
        'type' => 'price',
        'lower_range' => 1,
        'upper_range' => 50000
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1000,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1001,
        "always_added" => 0
    ]);

    $result = $this->cart_service->manageCartSamples([
        "total_price"=>-1,
        'items' => [
            [ 'id' => 10 ],
            [ 'id' => 11 ],
            [ 'id' => 12 ],
        ]
        ]);

        $this-> assertCount(0, $result['samples_to_remove']);
        $this-> assertCount(0, $result['samples_to_add']);

   }

   public function test_with_2_sets_and_2_samples(): void
   {
    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    $set2 = SFSSet::Create([
        "name" => "test2",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1000,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1001,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set2->id,
        "product_id" => 1002,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set2->id,
        "product_id" => 1003,
        "always_added" => 0
    ]);

    $result = $this->cart_service->manageCartSamples([
        "total_price"=>40,
        'items' => [
            [ 'id' => 10 ],
            [ 'id' => 11 ],
            [ 'id' => 12 ],
        ]
        ]);

        $this-> assertCount(0, $result['samples_to_remove']);
        $this-> assertCount(4, $result['samples_to_add']);
        $this-> assertContains(1000, $result['samples_to_add']);
        $this-> assertContains(1001, $result['samples_to_add']);
        $this-> assertContains(1002, $result['samples_to_add']);
        $this-> assertContains(1003, $result['samples_to_add']);

   }

   public function test_with_2_samples_and_quantity_4(): void
   {
    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 4,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1000,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1001,
        "always_added" => 0
    ]);

    $result = $this->cart_service->manageCartSamples([
        "total_price"=>50,
        'items' => [
            [ 'id' => 10 ],
            [ 'id' => 11 ],
            [ 'id' => 12 ],
        ]
        ]);

        $this-> assertCount(0, $result['samples_to_remove']);
        $this-> assertCount(4, $result['samples_to_add']);
        $this-> assertContains(1000, $result['samples_to_add']);
        $this-> assertContains(1001, $result['samples_to_add']);
   }

   public function test_with_samples_to_remove(): void
   {

    Cache::put("ShopifyFreeSamples.product_list", [
        [
            'variants' => [
                ['id' => 1000],
                ['id' => 1001],
            ]
        ]
    ]);
    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1000,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1001,
        "always_added" => 0
    ]);

    $result = $this->cart_service->manageCartSamples([
        "total_price"=>50,
        'items' => [
            [ 'id' => 1000 ],
            [ 'id' => 1001 ],
            [ 'id' => 1002 ],
        ]
        ]);

    $this-> assertCount(2,$result['samples_to_remove']);
    $this-> assertCount(2,$result['samples_to_add']);
    $this-> assertContains(1000, $result['samples_to_add']);
    $this-> assertContains(1001, $result['samples_to_add']);
    $this-> assertContains(1000, $result['samples_to_remove']);
    $this-> assertContains(1001, $result['samples_to_remove']);

   }

   public function test_2_sets_with_4_cache_samples(): void
   {

    Cache::put("ShopifyFreeSamples.product_list", [
        [
            'variants' => [
                ['id' => 1000],
                ['id' => 1001],
                ['id' => 1002],
                ['id' => 1003],
            ]
        ]
    ]);

    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    $set2 = SFSSet::Create([
        "name" => "test",
        "active" => false,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1000,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set->id,
        "product_id" => 1001,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set2->id,
        "product_id" => 1002,
        "always_added" => 0
    ]);

    SFSProduct::create([
        "sfs_set_id" => $set2->id,
        "product_id" => 1003,
        "always_added" => 0
    ]);

    $result = $this->cart_service->manageCartSamples([
        "total_price"=>50,
        'items' => [
            [ 'id' => 1000 ],
            [ 'id' => 1001 ],
            [ 'id' => 1002 ],
            [ 'id' => 1003 ],
            [ 'id' => 1004 ],
            [ 'id' => 1005 ],
        ]
        ]);

    $this-> assertCount(4,$result['samples_to_remove']);
    $this-> assertCount(2,$result['samples_to_add']);
    $this-> assertContains(1000, $result['samples_to_add']);
    $this-> assertContains(1001, $result['samples_to_add']);
    $this-> assertContains(1000, $result['samples_to_remove']);
    $this-> assertContains(1001, $result['samples_to_remove']);

   }

}
