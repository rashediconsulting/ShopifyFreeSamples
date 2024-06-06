<?php

namespace RashediConsulting\ShopifyFreeSamples\Services;

use RashediConsulting\ShopifyFreeSamples\Models\SFSProduct;

class ApiService{

    public $shopify_api;
    public $last_response;

    public function getApiConnection(){
        if(is_null($this->shopify_api)){
            $this->shopify_api = \Signifly\Shopify\Factory::fromArray([
                'access_token' => config('shopifyfreesamples.shopify_store_token', ''),
                'domain'=> config('shopifyfreesamples.shopify_domain', ''),
                'api_version'=> config('shopifyfreesamples.shopify_api_version', '2021-01'),
            ]);
        }

        return $this->shopify_api;
    }


	public function getAllProducts(){

        $shopify = $this->getApiConnection();

        $pages = $shopify->paginateProducts(['limit' => 250, 'collection_id' => 469976482074]); // returns Cursor

        $tmp_products = collect();

        foreach ($pages as $page) {
            // $page is a Collection of ApiResources
            $tmp_products = $tmp_products->merge($page);
        }

        $product_list = [];
        foreach ($tmp_products as $t_prd) {
            $product_list[] = $t_prd->toArray();
        }

        return $product_list;
	}

    public function graphQlQuery($query){

        if(is_null($this->shopify_api)){
            $this->shopify_api = $this->getApiConnection();
        }

        $pending_request = $this->shopify_api->graphQl();

        $pending_request->withBody(json_encode(['query' => $query]));

        $response = $pending_request->post('');

        $this->last_response = $response;

        return json_decode($response->getBody()->getContents());
    }

}