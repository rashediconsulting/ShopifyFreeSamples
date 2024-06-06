<?php

namespace RashediConsulting\ShopifyFreeSamples\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use RashediConsulting\ShopifyFreeSamples\Services\CartService;
use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;

class ShopifyFreeSamplesController extends Controller
{
    public $cart_service;

    function __construct(CartService $cart_service) {

        $this->cart_service = $cart_service;
    }

    public function freeSamples(){
        return view("ShopifyFreeSamples::pages.sample-set-list");
    }

    public function sampleSetDetail(SFSSet $sfs_set){
        return view("ShopifyFreeSamples::pages.product-list", ["sfs_set" => $sfs_set]);
    }

    public function updatedProduct()
    {
        Artisan::call('ShopifyFreeSamples:update-product-cache');
        return response()->json(["success" => true]);
    }

    public function updatedCheckout(Request $request)
    {
        /*
            Example of the recieved data:
            $request->all() = (
                [token] => Z2NwLXVzLWNlbnRyYWwxOjAxSE1YQkhXRUFEQThEWTEzVEpOOVRZRjRZ
                [note] =>
                [attributes] => Array
                    (
                    )

                [original_total_price] => 6500
                [total_price] => 6500
                [total_discount] => 0
                [total_weight] => 215
                [item_count] => 1
                [items] => Array
                    (
                        [0] => Array
                            (
                                [id] => 45226876764442
                                [properties] => Array
                                    (
                                    )

                                [quantity] => 1
                                [variant_id] => 45226876764442
                                [key] => 45226876764442:af6d2462-98d3-4281-88f6-529dd1a2cf69
                                [title] => CLEAN - 150ml
                                [price] => 6500
                                [original_price] => 6500
                                [discounted_price] => 6500
                                [line_price] => 6500
                                [original_line_price] => 6500
                                [total_discount] => 0
                                [discounts] => Array
                                    (
                                    )

                                [sku] => clean
                                [grams] => 215
                                [vendor] => DOCTOR Mi!
                                [taxable] => 1
                                [product_id] => 8312604393754
                                [product_has_only_default_variant] =>
                                [gift_card] =>
                                [final_price] => 6500
                                [final_line_price] => 6500
                                [url] => /products/clean-reinigungsgel?variant=45226876764442
                                [featured_image] => Array
                                    (
                                        [aspect_ratio] => 1.137
                                        [alt] => CLEAN - DoctorMiRc
                                        [height] => 1425
                                        [url] => https://cdn.shopify.com/s/files/1/0737/2924/5466/products/CLEAN1620x1425px.png?v=1686561445
                                        [width] => 1620
                                    )

                                [image] => https://cdn.shopify.com/s/files/1/0737/2924/5466/products/CLEAN1620x1425px.png?v=1686561445
                                [handle] => clean-reinigungsgel
                                [requires_shipping] => 1
                                [product_type] => Reinigung
                                [product_title] => CLEAN
                                [product_description] => {{ ... }}
                                [variant_title] => 150ml
                                [variant_options] => Array
                                    (
                                        [0] => 150ml
                                    )

                                [options_with_values] => Array
                                    (
                                        [0] => Array
                                            (
                                                [name] => Größe
                                                [value] => 150ml
                                            )

                                    )

                                [line_level_discount_allocations] => Array
                                    (
                                    )

                                [line_level_total_discount] => 0
                                [quantity_rule] => Array
                                    (
                                        [min] => 1
                                        [max] =>
                                        [increment] => 1
                                    )

                                [has_components] =>
                            )

                    )

                [requires_shipping] => 1
                [currency] => EUR
                [items_subtotal_price] => 6500
                [cart_level_discount_applications] => Array
                    (
                    )

                [shop] => doctormirc.myshopify.com
                [logged_in_customer_id] => 7789081755930
                [path_prefix] => /apps/dmi
                [timestamp] => 1706095033
                [signature] => 1b4bc1c4027966da6a73d949933ba1e2b9e576f8f370c9659ce687eb7869a2b5
            )
        \Log::info(print_r($request->all(), true));
        */

        $data = collect($request->all());


        if(isset($data["items"])){
            $result = $this->cart_service->manageCartSamples($data);
            return response()->json($result);
        }else{
            return response()->json(["success"=>true, "message"=>$data]);
        }
    }

    public function addSamplesToCompletedOrder(Request $request){

            $result = $this->cart_service->addRemoveGraphQlSamples([], []);
        $data = collect($request->all());

        if(isset($data["items"]) || true){
            $samples = $this->cart_service->manageCartSamples($data);
            
            $result = $this->cart_service->addRemoveGraphQlSamples($data, $samples);

            return response()->json($result);
        }else{
            return response()->json(["success"=>true, "message"=>$data]);
        }
    }
}
