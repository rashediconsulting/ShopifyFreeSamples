<?php
namespace RashediConsulting\ShopifyFreeSamples\Http\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

use RashediConsulting\ShopifyFreeSamples\\Models\SFSSet;
use RashediConsulting\ShopifyFreeSamples\\Models\SFSProduct;

class ProductList extends Component
{
    public $sample_set_id;
    public SFSSet $free_sample_set;
    public $product_list = [];
    public $free_sample_list = [];

    public $messages=[];

    public $name;
    public $active;
    public $quantity;
    public $display_in_checkout;
    public $repeatable;

    public function boot()
    {
        $this->free_sample_set = SFSSet::firstOrCreate([
            'id' => $this->sample_set_id
        ],
        [
            'name' => 'Default set',
            'active' => true,
            'quantity' => '2',
            'display_in_checkout' => false,
            'repeatable' => false,
        ]);
        $this->name = $this->free_sample_set->name;
        $this->active = $this->free_sample_set->active == 1;
        $this->quantity = $this->free_sample_set->quantity;
        $this->display_in_checkout = $this->free_sample_set->display_in_checkout == 1;
        $this->repeatable = $this->free_sample_set->repeatable == 1;

        $raw_product_list = Cache::get("ShopifyFreeSamples.product_list");
        $tmp_free_sample_list = SFSProduct::where("sfs_set_id", "=", 1)->get()->pluck("product_id");

        $this->free_sample_list = collect();
        $this->product_list = collect();

        foreach ($raw_product_list as $prd) {
            if($tmp_free_sample_list->contains($prd["id"])){
                $this->free_sample_list[$prd["id"]] = $prd;
            }else{
                $this->product_list[$prd["id"]] = $prd;
            }
        }
    }

    public function render()
    {
        return view('ShopifyFreeSamples::components.product-list');
    }

    public function addProductAsFreeSample($product_id){
        SFSProduct::create([
            "SFS_set_id" => 1,
            "product_id" => $product_id,
            "allways_added" => 0
        ]);

        $this->free_sample_list[$product_id] = $this->product_list->pull($product_id);
    }

    public function removeProductFromFreeSample($product_id){
        SFSProduct::whereProductId($product_id)->where("sfs_set_id", "=", 1)->delete();
        $this->product_list[$product_id] = $this->free_sample_list->pull($product_id);
    }

    public function saveChanges(){
        $this->free_sample_set->name = $this->name;
        $this->free_sample_set->active = $this->active == 1;
        $this->free_sample_set->quantity = $this->quantity;
        $this->free_sample_set->display_in_checkout = $this->display_in_checkout == 1;
        $this->free_sample_set->repeatable = $this->repeatable == 1;

        $this->free_sample_set->save();

        $this->messages[]= 'Changes successfully saved.';
    }
}