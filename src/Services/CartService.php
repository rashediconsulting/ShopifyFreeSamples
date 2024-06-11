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
		
		$samples_list = collect(Cache::get("ShopifyFreeSamples.product_list"))->pluck("variants.*.id")->flatten();
		$excluded_products = collect(Cache::get("ShopifyFreeSamples.excluded_product_list"))->pluck("variants.*.id")->flatten();

		$prd_in_cart = isset($cart_data["items"]) ? collect($cart_data["items"])->pluck("id") : collect($cart_data["line_items"])->pluck("variant_id");
		
		$prd_without_samples = $prd_in_cart->diff($samples_list);
		$remaining_products = $prd_without_samples->diff($excluded_products)->toArray();

		//dd(compact(['samples_list', 'excluded_products', 'prd_in_cart', 'prd_without_samples', 'remaining_products']));

		$data = [
			"samples_to_remove" => $prd_in_cart->intersect($samples_list)->toArray(),
			"samples_to_add" => [],
			"message" => 'ok',
		];
		//IF all products in cart are samples we don't have to add new ones. Just remove the existing ones.
		if(count($remaining_products) > 0){
			foreach ($sample_sets as $smp_set) {
				$data["samples_to_add"] = array_merge($data["samples_to_add"], $this->addSamples($smp_set));
			}
		}else{
			$data['message'] = 'All products are samples or excluded from samples';
			\Log::info("Processing: ");
			\Log::info("\tId: " . $cart_data["id"]);
			\Log::info("\tMessage: " . $data['message']);
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

		//\Log::info("Order processing:");
		//\Log::info("\tId: " . $data["id"]);

		$order_gid = "gid://shopify/Order/" . $data["id"];

		$queryString = <<<QUERY
		mutation beginEdit{
		 orderEditBegin(id: "$order_gid"){
			calculatedOrder{
			  id,
		      lineItems(first: 99){
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
			},
		    userErrors {
		      field
		      message
		    }
		  }
		}
		QUERY;

		$parsed_response = $this->api_service->graphQlQuery($queryString);

		$remove_samples_calculated_order = $parsed_response?->data?->orderEditBegin?->calculatedOrder?->id;
		if(!empty($remove_samples_calculated_order)){
			foreach ($samples["samples_to_remove"] as $sample_id) {

				foreach ($parsed_response->data->orderEditBegin->calculatedOrder->lineItems->edges as $line_item) {

					$line_item_gid = $line_item->node->id;
					$variant = $line_item->node->variant->id;
					if("gid://shopify/ProductVariant/" . $sample_id == $variant){
						$remove_sample = <<<QUERY
						mutation increaseLineItemQuantity {
						  orderEditSetQuantity(id: "$remove_samples_calculated_order", lineItemId: "$line_item_gid", quantity: 0) {
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

						$remove_line_response = $this->api_service->graphQlQuery($remove_sample);

						$user_errors = $remove_line_response?->data?->orderEditSetQuantity?->userErrors;

						if(count($user_errors) > 0){
							$error_list = '';
							foreach ($user_errors as $item) {
							    if (!empty($item->message)) {
							        $error_list .= "\t\t" . $item->message . PHP_EOL;
							    }
							}

			        		\Log::info("Order processing error:");
			        		\Log::info("\tGid: " . $order_gid);
			        		\Log::info("\tError removing line item: " . $line_item_gid);
			        		\Log::info("\tMessages:");
			        		\Log::info($error_list);

						}
					}
				}
			}

			$message = str_replace(["{","}", "\""], "", json_encode([
				"removed" => array_values($samples["samples_to_remove"])
			]));

			$commit_remove_edits = <<<QUERY
			mutation commitEdit {
			  orderEditCommit(id: "$remove_samples_calculated_order", notifyCustomer: false, staffNote: "Samples removed using GraphQL: $message") {
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

			$remove_response = $this->api_service->graphQlQuery($commit_remove_edits);

			$queryString = <<<QUERY
			mutation beginEdit{
			 orderEditBegin(id: "$order_gid"){
				calculatedOrder{
				  id,
			      lineItems(first: 99){
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
			QUERY;

			$parsed_response = $this->api_service->graphQlQuery($queryString);

			$add_samples_calculated_order = $parsed_response?->data?->orderEditBegin?->calculatedOrder?->id;

			foreach (array_count_values($samples["samples_to_add"]) as $sample_id => $sample_count) {
				$add_sample = <<<QUERY
				mutation addVariantToOrder{
				  orderEditAddVariant(id: "$add_samples_calculated_order", variantId: "gid://shopify/ProductVariant/$sample_id", quantity: $sample_count){
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

				$add_sample_response = $this->api_service->graphQlQuery($add_sample);

				$user_errors = $add_sample_response?->data?->orderEditAddVariant->userErrors;

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

			$message = str_replace(["{","}", "\""], "", json_encode([
				"added" => array_count_values($samples["samples_to_add"])
			]));


			$commit_edits = <<<QUERY
			mutation commitEdit {
			  orderEditCommit(id: "$add_samples_calculated_order", notifyCustomer: false, staffNote: "Samples added using GraphQL: $message") {
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

			$add_response = $this->api_service->graphQlQuery($commit_edits);
			//\Log::info("Samples added using GraphQL: " . $samples['message']);

			return true;
		}else{
			\Log::info("Order mutation can not be created:");
			\Log::info("\t" . $parsed_response?->data?->orderEditBegin?->userErrors[0]->message);
		}
	}
}