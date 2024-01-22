<?php
namespace RashediConsulting\ShopifyFreeSamples\Http\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;
use RashediConsulting\ShopifyFreeSamples\Models\SFSProduct;

class ProductList extends Component
{
    public $sample_set_id;
    public SFSSet $free_sample_set;
    public $raw_product_list = [];
    public $raw_free_sample_list = [];
    public $product_list = [];
    public $free_sample_list = [];

    public $messages=[];

    public $filter;

    public $name;
    public $active;
    public $quantity;
    public $display_in_checkout;
    public $repeatable;

    public function boot()
    {
        $this->free_sample_set = SFSSet::firstOrCreate(
            [
                'id' => $this->sample_set_id
            ],
            [
                'name' => 'Default set',
                'active' => true,
                'quantity' => '2',
                'display_in_checkout' => false,
                'repeatable' => false,
            ]
        );

        $this->name = $this->free_sample_set->name;
        $this->active = $this->free_sample_set->active == 1;
        $this->quantity = $this->free_sample_set->quantity;
        $this->display_in_checkout = $this->free_sample_set->display_in_checkout == 1;
        $this->repeatable = $this->free_sample_set->repeatable == 1;

        $this->raw_product_list = collect(Cache::get("ShopifyFreeSamples.product_list"));
        $this->raw_free_sample_list = SFSProduct::where("sfs_set_id", "=", 1)->get()->pluck("product_id");
    }

    public function render()
    {
        $this->applyFilter();
        return view('ShopifyFreeSamples::components.product-list');
    }

    public function applyFilter(){
        $filtered_products = $this->raw_product_list->filter(function ($sample) {
            return strpos(strtolower($sample["title"]), strtolower($this->filter)) !== false;
        });


        $this->free_sample_list = collect();
        $this->product_list = collect();

        foreach ($filtered_products as $prd) {
            $in_sample_list = $prd;
            $out_sample_list = $prd;

            $in_sample_list["variants"] = [];
            $out_sample_list["variants"] = [];

            foreach ($prd["variants"] as $variant) {
                if($this->raw_free_sample_list->contains($variant["id"])){
                    $in_sample_list["variants"][] = $variant;
                }else{
                    $out_sample_list["variants"][] = $variant;
                }
            }

            if(count($in_sample_list["variants"])){
                $this->free_sample_list[] = $in_sample_list;
            }

            if(count($out_sample_list["variants"])){
                $this->product_list[] = $out_sample_list;
            }
        }


    }

    public function addProductAsFreeSample($variant_id){
        SFSProduct::create([
            "SFS_set_id" => 1,
            "product_id" => $variant_id,
            "always_added" => 0
        ]);

    }

    public function removeProductFromFreeSample($variant_id){
        SFSProduct::whereProductId($variant_id)->where("sfs_set_id", "=", 1)->delete();
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