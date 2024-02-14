<?php

namespace RashediConsulting\ShopifyFreeSamples\Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use RashediConsulting\ShopifyFreeSamples\Models\SFSRule;
use RashediConsulting\ShopifyFreeSamples\Services\ApiService;

class CartTest extends TestCase
{
    /**
     * A basic test example.
     */
   public function test_date_lower_than(): void
   {
    $rule = SFSRule::Make([
        'type' => 'date',
        'lower_range' => \Carbon\Carbon::create(2000,01,01),
        'upper_range' => \Carbon\Carbon::create(2001,01,01)
    ]);
    $this-> assertFalse($rule->passes(["total_price" => 0]));


   }

   public function test_date_upper_than(): void
   {
    $rule = SFSRule::Make([
        'type' => 'date',
        'lower_range' => \Carbon\Carbon::create(3000,01,01),
        'upper_range' => \Carbon\Carbon::create(4000,01,01)
    ]);
    $this-> assertFalse($rule->passes(["total_price" => 0]));
   }

   public function test_date_not_lower_than(): void
   {
    $rule = SFSRule::Make([
        'type' => 'date',
        'lower_range' => \Carbon\Carbon::create(2000,01,01),
        'upper_range' => \Carbon\Carbon::create(3000,01,01)
    ]);
    $this-> assertTrue($rule->passes(["total_price" => 0]));
   }


   public function test_price_lower_than(): void
   {
    $rule = SFSRule::Make([
        'type' => 'price',
        'lower_range' => 1,
        'upper_range' => 5000
    ]);
    $this-> assertFalse($rule->passes(["total_price" => 10000]));
   }

   public function test_price_upper_than(): void
   {
    $rule = SFSRule::Make([
        'type' => 'price',
        'lower_range' => 1,
        'upper_range' => 3000
    ]);
    $this-> assertFalse($rule->passes(["total_price" => 0]));
   }

   public function test_price_not_upper_than(): void
   {
    $rule = SFSRule::Make([
        'type' => 'price',
        'lower_range' => 1,
        'upper_range' => 3000
    ]);
    $this-> assertTrue($rule->passes(["total_price" => 1500]));
   }
}
