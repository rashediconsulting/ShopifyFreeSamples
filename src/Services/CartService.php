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
			return ["success" => false, "error" => "Cart missing line items"];
		}

		$samples = $this->getEligibleSamples();

		/*if($this->cartHasOnlySamples($cart_data, $samples)){
			return ["success" => false, "error" => "Cart has only samples"];
		}*/

		$samples_to_remove = $this->removeExistingSamples($cart_data, $samples);

		$samples_to_add = $this->addSamples($cart_data, $samples, SFSSet::find(1));

		return [
			"samples_to_remove" => $samples_to_remove,
			"samples_to_add" => $samples_to_add
		];
	}

	public function getEligibleSamples(){
        return SFSProduct::where("sfs_set_id", "=", 1)->get();
	}

	protected function cartHasOnlySamples($cart_data, $sample_list){
		$id_list = $sample_list->pluck("product_id");
		$only_samples = true;

		foreach ($cart_data["line_items"] as $line_item) {
			$only_samples &= $id_list->contains($line_item);
		}

		return $only_samples;
	}

	protected function removeExistingSamples($cart_data, $sample_list){
		$id_list = $sample_list->pluck("product_id");
		$only_samples = true;

		$samples_to_remove = [];

		foreach ($cart_data["line_items"] as $line_item) {
			if($id_list->contains($line_item)){
				$samples_to_remove[] = $line_item;
			};
		}

		return $samples_to_remove;
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
			$samples_to_add[] = $randomly_added_samples->random()->product_id;
		}

		return $samples_to_add;
	}
}