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

	public function addRemoveGraphQlSamples($data = [], $samples = []){

		$order_gid = "gid://shopify/Order/" . $data["id"];

		$queryString = <<<QUERY
		mutation beginEdit{
		 orderEditBegin(id: "$order_gid"){
			calculatedOrder{
			  id,
			  CalculatedLineItems (first:99){
			  		edges{
			  			node{
					  		id,
					  		variant{
					  			id
					  		},
					  		quantity
			  			}
			  		}
			  	}
			  order{
			  	lineItems (first:99){
			  		edges{
			  			node{
					  		id,
					  		variant{
					  			id
					  		},
					  		quantity
			  			}
			  		}
			  	}
			  }
			}
		  }
		}
		QUERY;

		$parsed_response = $this->api_service->graphQlQuery($queryString);

		dd($parsed_response);
		$calculated_order = $parsed_response?->data?->orderEditBegin?->calculatedOrder?->id;
		if(!empty($calculated_order)){

			foreach (array_count_values($samples["samples_to_remove"]) as $sample_id => $sample_count) {

				foreach ($parsed_response->data->orderEditBegin->calculatedOrder->order->lineItems->edges as $line_item) {

					$line_item_gid = $line_item->node->id;
					$variant = $line_item->node->variant->id;
					if("gid://shopify/ProductVariant/" . $sample_id == $variant){
						$remove_sample = <<<QUERY
						mutation increaseLineItemQuantity {
						  orderEditSetQuantity(id: "$calculated_order", lineItemId: "$line_item_gid", quantity: 0) {
						    calculatedOrder {
						      id
						      addedLineItems(first: 5) {
						        edges {
						          node {
						            id
						            quantity
						          }
						        }
						      }
						    }
						    userErrors {
						      field
						      message
						    }
						  }
						}
						QUERY;
						dd($remove_sample);
						$parsed_response = $this->api_service->graphQlQuery($remove_sample);
						dd($parsed_response);
						$user_errors = $parsed_response?->data?->orderEditAddVariant->userErrors;

						if(count($user_errors) > 0){
							$error_list = '';
							foreach ($user_errors as $item) {
							    if (!empty($item->message)) {
							        $error_list .= "\t\t" . $item->message . PHP_EOL;
							    }
							}

			        		\Log::info("Order processing error:");
			        		\Log::info("\tGid: " . $order_gid);
			        		\Log::info("\tMessages:");
			        		\Log::info($error_list);

						}
					}
				}
			}

			foreach (array_count_values($samples["samples_to_add"]) as $sample_id => $sample_count) {
				$add_sample = <<<QUERY
				mutation addVariantToOrder{
				  orderEditAddVariant(id: "$calculated_order", variantId: "gid://shopify/ProductVariant/$sample_id", quantity: $sample_count){
					calculatedOrder {
					  id
					  addedLineItems(first:5) {
						edges {
						  node {
							id
							quantity
						  }
						}
					  }
					}
					userErrors {
					  field
					  message
					}
				  }
				}
				QUERY;

				$parsed_response = $this->api_service->graphQlQuery($add_sample);

				$user_errors = $parsed_response?->data?->orderEditAddVariant->userErrors;

				if(count($user_errors) > 0){
					$error_list = '';
					foreach ($user_errors as $item) {
					    if (!empty($item->message)) {
					        $error_list .= "\t\t" . $item->message . PHP_EOL;
					    }
					}

	        		\Log::info("Order processing error:");
	        		\Log::info("\tGid: " . $order_gid);
	        		\Log::info("\tMessages:");
	        		\Log::info($error_list);

				}
			}

			$commit_edits = <<<QUERY
			mutation commitEdit {
			  orderEditCommit(id: "$calculated_order", notifyCustomer: false, staffNote: "Samples added using GraphQL") {
				order {
				  id
				}
				userErrors {
				  field
				  message
				}
			  }
			}
			QUERY;

			$parsed_response = $this->api_service->graphQlQuery($commit_edits);
			dd($parsed_response, $this->api_service->last_response);

		}else{
			throw new Exception("Order mutation can not be created", 1);
		}





	}
}