<?php

namespace RashediConsulting\ShopifyFreeSamples\App\Models;

use Illuminate\Database\Eloquent\Model;


class SFSProduct extends Model
{

    public $timestamps = false;

    public $table = "sfs_products";

    protected $fillable = [
        "SFS_set_id",
        "product_id",
        "allways_added",
    ];
}
