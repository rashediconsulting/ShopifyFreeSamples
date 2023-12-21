<?php
namespace RashediConsulting\ShopifyFreeSamples\App\Views\Components;

use Illuminate\View\Component;
use Filament\Tables\Table;

class ProductList extends Component
{
    public $product_list;

    public function __construct()
    {
        $shopify = \Signifly\Shopify\Factory::fromArray([
            'access_token' => config('shopifyfreesamples.shopify_store_token', ''),
            'domain'=> config('shopifyfreesamples.shopify_domain', ''),
            'api_version'=> config('shopifyfreesamples.shopify_api_version', '2021-01'),
        ]);

        $pages = $shopify->paginateProducts(['limit' => 100]); // returns Cursor

        $results = collect();

        foreach ($pages as $page) {
            // $page is a Collection of ApiResources
            $results = $results->merge($page);
        }
        dd($results[0]);
        $this->product_list = $results;
    }

    public function render()
    {
        return view('ShopifyFreeSamples::components.product-list');
    }
}


 
