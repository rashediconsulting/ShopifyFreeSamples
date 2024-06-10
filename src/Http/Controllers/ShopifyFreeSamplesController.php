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

        /*
        WEBHOOK example
        [items:protected] => Array
        (
            [id] => 820982911946154508
            [admin_graphql_api_id] => gid://shopify/Order/820982911946154508
            [app_id] =>
            [browser_ip] =>
            [buyer_accepts_marketing] => 1
            [cancel_reason] => customer
            [cancelled_at] => 2024-06-10T12:37:36+02:00
            [cart_token] =>
            [checkout_id] =>
            [checkout_token] =>
            [client_details] =>
            [closed_at] =>
            [company] =>
            [confirmation_number] =>
            [confirmed] =>
            [contact_email] => jon@example.com
            [created_at] => 2024-06-10T12:37:36+02:00
            [currency] => EUR
            [current_subtotal_price] => 368.00
            [current_subtotal_price_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 368.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 368.00
                            [currency_code] => EUR
                        )

                )

            [current_total_additional_fees_set] =>
            [current_total_discounts] => 0.00
            [current_total_discounts_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 0.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 0.00
                            [currency_code] => EUR
                        )

                )

            [current_total_duties_set] =>
            [current_total_price] => 368.00
            [current_total_price_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 368.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 368.00
                            [currency_code] => EUR
                        )

                )

            [current_total_tax] => 0.00
            [current_total_tax_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 0.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 0.00
                            [currency_code] => EUR
                        )

                )

            [customer_locale] => de
            [device_id] =>
            [discount_codes] => Array
                (
                )

            [duties_included] =>
            [email] => jon@example.com
            [estimated_taxes] =>
            [financial_status] => voided
            [fulfillment_status] => pending
            [landing_site] =>
            [landing_site_ref] =>
            [location_id] =>
            [merchant_of_record_app_id] =>
            [name] => #9999
            [note] =>
            [note_attributes] => Array
                (
                )

            [number] => 234
            [order_number] => 1234
            [order_status_url] => https://doctormi.de/2175205458/orders/123456abcd/authenticate?key=abcdefg
            [original_total_additional_fees_set] =>
            [original_total_duties_set] =>
            [payment_gateway_names] => Array
                (
                    [0] => visa
                    [1] => bogus
                )

            [phone] =>
            [po_number] =>
            [presentment_currency] => EUR
            [processed_at] => 2024-06-10T12:37:36+02:00
            [reference] =>
            [referring_site] =>
            [source_identifier] =>
            [source_name] => web
            [source_url] =>
            [subtotal_price] => 358.00
            [subtotal_price_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 358.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 358.00
                            [currency_code] => EUR
                        )

                )

            [tags] => tag1, tag2
            [tax_exempt] =>
            [tax_lines] => Array
                (
                )

            [taxes_included] =>
            [test] => 1
            [token] => 123456abcd
            [total_discounts] => 20.00
            [total_discounts_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 20.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 20.00
                            [currency_code] => EUR
                        )

                )

            [total_line_items_price] => 368.00
            [total_line_items_price_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 368.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 368.00
                            [currency_code] => EUR
                        )

                )

            [total_outstanding] => 368.00
            [total_price] => 358.00
            [total_price_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 358.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 358.00
                            [currency_code] => EUR
                        )

                )

            [total_shipping_price_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 10.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 10.00
                            [currency_code] => EUR
                        )

                )

            [total_tax] => 0.00
            [total_tax_set] => Array
                (
                    [shop_money] => Array
                        (
                            [amount] => 0.00
                            [currency_code] => EUR
                        )

                    [presentment_money] => Array
                        (
                            [amount] => 0.00
                            [currency_code] => EUR
                        )

                )

            [total_tip_received] => 0.00
            [total_weight] => 0
            [updated_at] => 2024-06-10T12:37:36+02:00
            [user_id] =>
            [billing_address] => Array
                (
                    [first_name] => Steve
                    [address1] => 123 Shipping Street
                    [phone] => 555-555-SHIP
                    [city] => Shippington
                    [zip] => 40003
                    [province] => Kentucky
                    [country] => United States
                    [last_name] => Shipper
                    [address2] =>
                    [company] => Shipping Company
                    [latitude] =>
                    [longitude] =>
                    [name] => Steve Shipper
                    [country_code] => US
                    [province_code] => KY
                )

            [customer] => Array
                (
                    [id] => 115310627314723954
                    [email] => john@example.com
                    [created_at] =>
                    [updated_at] =>
                    [first_name] => John
                    [last_name] => Smith
                    [state] => disabled
                    [note] =>
                    [verified_email] => 1
                    [multipass_identifier] =>
                    [tax_exempt] =>
                    [phone] =>
                    [email_marketing_consent] => Array
                        (
                            [state] => not_subscribed
                            [opt_in_level] =>
                            [consent_updated_at] =>
                        )

                    [sms_marketing_consent] =>
                    [tags] =>
                    [currency] => EUR
                    [tax_exemptions] => Array
                        (
                        )

                    [admin_graphql_api_id] => gid://shopify/Customer/115310627314723954
                    [default_address] => Array
                        (
                            [id] => 715243470612851245
                            [customer_id] => 115310627314723954
                            [first_name] =>
                            [last_name] =>
                            [company] =>
                            [address1] => 123 Elm St.
                            [address2] =>
                            [city] => Ottawa
                            [province] => Ontario
                            [country] => Canada
                            [zip] => K2H7A8
                            [phone] => 123-123-1234
                            [name] =>
                            [province_code] => ON
                            [country_code] => CA
                            [country_name] => Canada
                            [default] => 1
                        )

                )

            [discount_applications] => Array
                (
                )

            [fulfillments] => Array
                (
                )

            [line_items] => Array
                (
                    [0] => Array
                        (
                            [id] => 866550311766439020
                            [admin_graphql_api_id] => gid://shopify/LineItem/866550311766439020
                            [attributed_staffs] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => gid://shopify/StaffMember/902541635
                                            [quantity] => 1
                                        )

                                )

                            [current_quantity] => 1
                            [fulfillable_quantity] => 1
                            [fulfillment_service] => manual
                            [fulfillment_status] =>
                            [gift_card] =>
                            [grams] => 120
                            [name] => RED
                            [price] => 199.00
                            [price_set] => Array
                                (
                                    [shop_money] => Array
                                        (
                                            [amount] => 199.00
                                            [currency_code] => EUR
                                        )

                                    [presentment_money] => Array
                                        (
                                            [amount] => 199.00
                                            [currency_code] => EUR
                                        )

                                )

                            [product_exists] => 1
                            [product_id] => 1568769245266
                            [properties] => Array
                                (
                                )

                            [quantity] => 1
                            [requires_shipping] => 1
                            [sku] => red
                            [taxable] => 1
                            [title] => RED
                            [total_discount] => 0.00
                            [total_discount_set] => Array
                                (
                                    [shop_money] => Array
                                        (
                                            [amount] => 0.00
                                            [currency_code] => EUR
                                        )

                                    [presentment_money] => Array
                                        (
                                            [amount] => 0.00
                                            [currency_code] => EUR
                                        )

                                )

                            [variant_id] => 15514836238418
                            [variant_inventory_management] => shopify
                            [variant_title] =>
                            [vendor] =>
                            [tax_lines] => Array
                                (
                                )

                            [duties] => Array
                                (
                                )

                            [discount_allocations] => Array
                                (
                                )

                        )

                    [1] => Array
                        (
                            [id] => 141249953214522974
                            [admin_graphql_api_id] => gid://shopify/LineItem/141249953214522974
                            [attributed_staffs] => Array
                                (
                                )

                            [current_quantity] => 1
                            [fulfillable_quantity] => 1
                            [fulfillment_service] => manual
                            [fulfillment_status] =>
                            [gift_card] =>
                            [grams] => 200
                            [name] => NIGHT
                            [price] => 169.00
                            [price_set] => Array
                                (
                                    [shop_money] => Array
                                        (
                                            [amount] => 169.00
                                            [currency_code] => EUR
                                        )

                                    [presentment_money] => Array
                                        (
                                            [amount] => 169.00
                                            [currency_code] => EUR
                                        )

                                )

                            [product_exists] => 1
                            [product_id] => 1568774258770
                            [properties] => Array
                                (
                                )

                            [quantity] => 1
                            [requires_shipping] => 1
                            [sku] => night
                            [taxable] => 1
                            [title] => NIGHT
                            [total_discount] => 0.00
                            [total_discount_set] => Array
                                (
                                    [shop_money] => Array
                                        (
                                            [amount] => 0.00
                                            [currency_code] => EUR
                                        )

                                    [presentment_money] => Array
                                        (
                                            [amount] => 0.00
                                            [currency_code] => EUR
                                        )

                                )

                            [variant_id] => 15514997325906
                            [variant_inventory_management] => shopify
                            [variant_title] =>
                            [vendor] =>
                            [tax_lines] => Array
                                (
                                )

                            [duties] => Array
                                (
                                )

                            [discount_allocations] => Array
                                (
                                )

                        )

                )

            [payment_terms] =>
            [refunds] => Array
                (
                )

            [shipping_address] => Array
                (
                    [first_name] => Steve
                    [address1] => 123 Shipping Street
                    [phone] => 555-555-SHIP
                    [city] => Shippington
                    [zip] => 40003
                    [province] => Kentucky
                    [country] => United States
                    [last_name] => Shipper
                    [address2] =>
                    [company] => Shipping Company
                    [latitude] =>
                    [longitude] =>
                    [name] => Steve Shipper
                    [country_code] => US
                    [province_code] => KY
                )

            [shipping_lines] => Array
                (
                    [0] => Array
                        (
                            [id] => 271878346596884015
                            [carrier_identifier] =>
                            [code] =>
                            [discounted_price] => 10.00
                            [discounted_price_set] => Array
                                (
                                    [shop_money] => Array
                                        (
                                            [amount] => 10.00
                                            [currency_code] => EUR
                                        )

                                    [presentment_money] => Array
                                        (
                                            [amount] => 10.00
                                            [currency_code] => EUR
                                        )

                                )

                            [is_removed] =>
                            [phone] =>
                            [price] => 10.00
                            [price_set] => Array
                                (
                                    [shop_money] => Array
                                        (
                                            [amount] => 10.00
                                            [currency_code] => EUR
                                        )

                                    [presentment_money] => Array
                                        (
                                            [amount] => 10.00
                                            [currency_code] => EUR
                                        )

                                )

                            [requested_fulfillment_service_id] =>
                            [source] => shopify
                            [title] => Generic Shipping
                            [tax_lines] => Array
                                (
                                )

                            [discount_allocations] => Array
                                (
                                )

                        )

                )

        )*/

        $data = $request->all();

        \Log::info("RAW order data");
        \Log::info(print_r($data, true));

        if(isset($data["line_items"])){

            $samples = $this->cart_service->manageCartSamples($data);
            \Log::info(print_r($samples, true));
            $result = $this->cart_service->addRemoveGraphQlSamples($data, $samples);

            return response()->json($result);
        }else{
            return response()->json(["success"=>true, "message"=>$data]);
        }
    }
}
