<?php
	
namespace RashediConsulting\ShopifyFreeSamples\Services;

use RashediConsulting\ShopifyFreeSamples\Models\SFSProduct;
use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;
use RashediConsulting\ShopifyFreeSamples\Services\ApiService;

class CartService{

	public $api_service;

    function __construct(ApiService $api_service) {

        $this->api_service = $api_service;
    }


	public function addSamplesToCart($cart_data){

		if(count($cart_data) == 0){
			return false;
		}

		$samples = $this->getEligibleSamples();

		if($this->cartHasOnlySamples($cart_data, $samples)){
			return false;
		}

		$this->removeExistingSamples($cart_data, $samples);

		$this->addSamples($cart_data, $samples, SFSSet::find(1));

		dd($samples);
	}

	public function getEligibleSamples(){
        return SFSProduct::where("sfs_set_id", "=", 1)->get();
	}

	protected function cartHasOnlySamples($cart_data, $sample_list){
		$id_list = $sample_list->pluck("product_id");
		$only_samples = true;
		
		foreach ($cart_data["line_items"] as $line_item) {
			$only_samples &= $id_list->contains($line_item["product_id"]);
		}

		return $only_samples;
	}

	protected function removeExistingSamples($cart_data, $sample_list){
		$id_list = $sample_list->pluck("product_id");
		$only_samples = true;
		
		foreach ($cart_data["line_items"] as $line_item) {
			if($id_list->contains($line_item["product_id"])){
				$cart_data = $this->api_service->removeSampleProduct($cart_data, $line_item["product_id"]);
			};
		}

		return $cart_data;
	}

	protected function addSamples($cart_data, $sample_list, $sample_set){


		$always_added_samples = $sample_list->filter(function ($sample) {
		    return $sample->always_added == 1;
		})->shuffle();

		$randomly_added_samples = $sample_list->filter(function ($sample) {
		    return $sample->always_added == 0;
		})->shuffle();

		$samples_to_add = collect();

		for ($i=0; $i < min($sample_set->quantity, $always_added_samples->count()); $i++) { 
			$samples_to_add[] = $always_added_samples[$i];
		}

		for ($i = count($samples_to_add); $i < $sample_set->quantity; $i++) { 
			$samples_to_add[] = $randomly_added_samples->random();
		}

		foreach ($samples_to_add as $sample) {			
			$cart_data = $this->api_service->addSampleProduct($cart_data, $sample->product_id);
		}

		dd($sample_set);

	}
}