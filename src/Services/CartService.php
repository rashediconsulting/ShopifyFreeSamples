<?php

namespace RashediConsulting\ShopifyFreeSamples\Services;

use Illuminate\Support\Facades\Cache;

use RashediConsulting\ShopifyFreeSamples\Models\SFSProduct;
use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;
use RashediConsulting\ShopifyFreeSamples\Services\ApiService;

class CartService{

	public $api_service;

    function __construct(ApiService $api_service) {

        $this->api_service = $api_service;
    }

    public function manageCartSamples($cart_data){

    	$sample_sets = $this->getEligibleSampleSets($cart_data);
        $all_products = collect(Cache::get("ShopifyFreeSamples.product_list"))->pluck("variants.*.id")->flatten();

        $prd_in_cart = collect($cart_data["items"])->pluck("id");

    	$data = [
			"samples_to_remove" => $prd_in_cart->intersect($all_products)->toArray(),
			"samples_to_add" => [],
		];

		//IF all products in cart are samples we don't have to add new ones. Just remove the existing ones.
		if(count($data["samples_to_remove"]) != $prd_in_cart->count()){
			foreach ($sample_sets as $smp_set) {
				$data["samples_to_add"] = array_merge($data["samples_to_add"], $this->addSamples($smp_set));
			}
		}

		return $data;
    }


	public function getEligibleSampleSets($cart_data){

		$ss = SFSSet::all();

		$sets_to_apply = [];

		foreach ($ss as $set) {
			if($set->passes($cart_data)){
				$sets_to_apply[] = $set;
			}
		}

        return $sets_to_apply;
	}

	protected function addSamples($sample_set){

		/*$always_added_samples = $sample_set->samples->filter(function ($sample) {
		    return $sample->always_added == 1;
		})->shuffle();*/

		/*$randomly_added_samples = $sample_set->samples->filter(function ($sample) {
		    return $sample->always_added == 0;
		})->shuffle();*/

		$samples_to_add = collect();

		for ($i=0; $i < (int) floor($sample_set->quantity / $sample_set->samples->count()); $i++) {
			$samples_to_add = $samples_to_add->merge($sample_set->samples->random($sample_set->samples->count()));
		}

		$samples_to_add = $samples_to_add->merge($sample_set->samples->random($sample_set->quantity % $sample_set->samples->count()));

		return $samples_to_add->pluck("product_id")->toArray();
	}
}