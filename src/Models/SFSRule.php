<?php

namespace RashediConsulting\ShopifyFreeSamples\Models;

use Illuminate\Database\Eloquent\Model;


class SFSRule extends Model
{

    public $timestamps = false;

    public $table = "sfs_rules";

    protected $fillable = [
        "id",
        "sfs_set_id",
        "type",
        "lower_range",
        "upper_range",
    ];

    public function getShortDescriptionAttribute(){
        $message = '';
        switch ($this->type) {
            case 'date':
                $message = 'Date range: ';
                break;

            case 'price':
                $message = 'Price range: ';
                break;

            default:
                return false;
                break;
        }

        return $message . $this->lower_range . " -> " . $this->upper_range;
    }

    public function passes($cart_data){

        switch ($this->type) {
            case 'date':
                return $this->lower_range < \Carbon\Carbon::now() && $this->upper_range > \Carbon\Carbon::now();
                break;

            case 'price':
                return $this->lower_range < $cart_data["total_price"] && $this->upper_range > $cart_data["total_price"];
                break;

            default:
                return false;
                break;
        }

    }
}
