<?php

namespace RashediConsulting\ShopifyFreeSamples\Models;

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

    public function rules(){
        return $this->hasMany(SFSRule::class, "sfs_set_id");
    }

    public function samples(){
        return $this->hasMany(SFSProduct::class, "sfs_set_id");
    }

    public function passes($cart_data){
        $passes = true;
        if(count($this->rules) == 0){
            $passes = true;
        }else{
            foreach ($this->rules as $rule) {
                $passes &= $rule->passes($cart_data);
            }
        }

        return $passes && $this->active;
    }
}
