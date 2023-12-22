<?php

namespace RashediConsulting\ShopifyFreeSamples\App\Models;

use Illuminate\Database\Eloquent\Model;


class SFSSet extends Model
{

    public $timestamps = false;

    public $table = "sfs_sets";

    protected $fillable = [
        "id",
        "name",
        "active",
        "quantity",
        "display_in_checkout",
        "repeatable",
    ];
}
