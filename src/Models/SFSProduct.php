<?php

namespace RashediConsulting\ShopifyFreeSamples\Models;

use Illuminate\Database\Eloquent\Model;


class SFSProduct extends Model
{

    public $timestamps = false;

    public $table = "sfs_products";

    protected $fillable = [
        "sfs_set_id",
        "product_id",
        "always_added",
    ];
}
