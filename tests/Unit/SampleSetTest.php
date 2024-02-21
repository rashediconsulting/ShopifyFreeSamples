<?php

namespace RashediConsulting\ShopifyFreeSamples\Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use RashediConsulting\ShopifyFreeSamples\Models\SFSRule;
use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;
use RashediConsulting\ShopifyFreeSamples\Services\CartService;

class SampleSetTest extends TestCase
{


    public $cart_service;

    public function setUp(): void {

        parent::setUp();

        $this->cart_service = app(CartService::class);
    }
    /**
     * A basic test example.
     */
   public function test_set_date_doesnt_pass(): void
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
        'type' => 'date',
        'lower_range' => \Carbon\Carbon::create(2000,01,01),
        'upper_range' => \Carbon\Carbon::create(2001,01,01)
    ]);

    $this-> assertFalse($set->passes(["total_price" => 0]));
   }

   public function test_set_date_passes(): void
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
        'type' => 'date',
        'lower_range' => \Carbon\Carbon::create(2000,01,01),
        'upper_range' => \Carbon\Carbon::create(3000,01,01)
    ]);

    $this-> assertTrue($set->passes(["total_price" => 0]));
   }

   public function test_set_price_doesnt_pass(): void
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

    $this-> assertFalse($set->passes(["total_price" => -1]));
   }

   public function test_set_price_passes(): void
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

    $this-> assertTrue($set->passes(["total_price" => 50]));
   }

   public function test_set_is_active(): void
   {
    $set = SFSSet::Create([
        "name" => "test",
        "active" => true,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    $this-> assertTrue($set->passes('active'));
   }

   public function test_set_is_not_active(): void
   {
    $set = SFSSet::Create([
        "name" => "test",
        "active" => false,
        "quantity" => 2,
        "display_in_checkout" => true,
        "repeatable" => true,
    ]);

    $this-> assertFalse($set->passes('active'));
   }

   public function test_set_date_and_price_doesnt_pass(): void
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

    SFSRule::Create([
        'sfs_set_id'=>$set->id,
        'type' => 'date',
        'lower_range' => \Carbon\Carbon::create(2000,01,01),
        'upper_range' => \Carbon\Carbon::create(3000,01,01)
    ]);

    $this-> assertFalse($set->passes(["total_price" => -1]));
   }

   public function test_set_date_and_price_pass(): void
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

    SFSRule::Create([
        'sfs_set_id'=>$set->id,
        'type' => 'date',
        'lower_range' => \Carbon\Carbon::create(2000,01,01),
        'upper_range' => \Carbon\Carbon::create(3000,01,01)
    ]);

    $this-> assertTrue($set->passes(["total_price" => 50]));
   }

   /*public function test_set_date_and_price_pass(): void
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

    SFSRule::Create([
        'sfs_set_id'=>$set->id,
        'type' => 'date',
        'lower_range' => \Carbon\Carbon::create(2000,01,01),
        'upper_range' => \Carbon\Carbon::create(3000,01,01)
    ]);

    $this-> assertTrue($set->passes(["total_price" =>50]));
   }*/

   /*public function test_date_upper_than(): void
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
   }*/
}
