<?php

namespace RashediConsulting\ShopifyFreeSamples\Tests\Livewire;

use RashediConsulting\ShopifyFreeSamples\Http\Livewire\SampleSetList;

use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;
use RashediConsulting\ShopifyFreeSamples\Models\SFSProduct;
use RashediConsulting\ShopifyFreeSamples\Models\SFSRule;


class SampleSetListTest extends TestCase
{

    public function setUp(): void {

        parent::setUp();

        Schema::disableForeignKeyConstraints();
        SFSSet::truncate();
        SFSProduct::truncate();
        SFSRule::truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function test_renders_successfully()
    {
        Livewire::test(SampleSetList::class)
            ->assertStatus(200);
    }

    public function test_component_exists_on_the_page()
    {
        $this->get(route('free-samples'))
            ->assertSeeLivewire(SampleSetList::class);
    }

    public function test_displays_sample_sets()
    {
        SFSSet::create([
            'name' => 'Test set 1',
            'active' => true,
            'quantity' => '2',
            'repeatable' => false,
            'display_in_checkout' => false,
        ]);

        SFSSet::create([
            'name' => 'Test set 2',
            'active' => true,
            'quantity' => '2',
            'repeatable' => false,
            'display_in_checkout' => false,
        ]);


        Livewire::test(SampleSetList::class)
            ->assertSee('Test set 1')
            ->assertSee('Test set 2');
    }

    public function test_deletes_sample_sets()
    {
        SFSSet::create([
            'name' => 'Test set 1',
            'active' => true,
            'quantity' => '2',
            'repeatable' => false,
            'display_in_checkout' => false,
        ]);

        SFSSet::create([
            'name' => 'Test set 2',
            'active' => true,
            'quantity' => '2',
            'repeatable' => false,
            'display_in_checkout' => false,
        ]);


        Livewire::test(SampleSetList::class)
            ->assertSee('Test set 1')
            ->assertSee('Test set 2')
            ->call('deleteSampleSet', 1)
            ->assertDontSee('Test set 1')
            ->assertSee('Test set 2');

    }

    public function test_deletes_sample_sets_with_rules()
    {
        $set_1 = SFSSet::create([
            'name' => 'Test set 1',
            'active' => true,
            'quantity' => '2',
            'repeatable' => false,
            'display_in_checkout' => false,
        ]);

        SFSRule::create([
            'sfs_set_id' => $set_1->id,
            'type' => "date",
            'lower_range' => "20000101",
            'upper_range' => "20000102",
        ]);

        $set_2 = SFSSet::create([
            'name' => 'Test set 2',
            'active' => true,
            'quantity' => '2',
            'repeatable' => false,
            'display_in_checkout' => false,
        ]);

        SFSRule::create([
            'sfs_set_id' => $set_2->id,
            'type' => "date",
            'lower_range' => "20000101",
            'upper_range' => "20000102",
        ]);

        Livewire::test(SampleSetList::class)
            ->assertSee('Test set 1')
            ->assertSee('Test set 2')
            ->call('deleteSampleSet', 1)
            ->assertDontSee('Test set 1')
            ->assertSee('Test set 2');

        $this->assertFalse(SFSRule::whereSetId(1)->exist());

    }
}