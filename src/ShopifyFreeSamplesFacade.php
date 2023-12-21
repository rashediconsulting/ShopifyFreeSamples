<?php

namespace RashediConsulting\ShopifyFreeSamples;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RashediConsulting\ShopifyFreeSamples\Skeleton\SkeletonClass
 */
class ShopifyFreeSamplesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shopifyfreesamples';
    }
}
