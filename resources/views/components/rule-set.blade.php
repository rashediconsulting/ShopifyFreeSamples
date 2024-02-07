<div class="p-5 mt-3  w-full">
    <h6>If you don't apply any rule, this sample set will be applied to every cart</h6>
    <div class="table-auto mt-5 bg-white w-full">
        <div class="grid grid-cols-4">
            <div class="text-start font-bold">Type</div>
            <div rowspan="2" class="text-start font-bold">From</div>
            <div class="text-start font-bold">To</div>
        </div>

        @foreach ($sfs_set->rules as $rule)
            @dump($rule->short_description)
            @livewire('ShopifyFreeSamples::rule-detail', ['sfs_set_id' => $sfs_set->id, 'rule' => $rule])
        @endforeach

    </div>

    @livewire('ShopifyFreeSamples::rule-detail', ['sfs_set_id' => $sfs_set->id, 'rule' => RashediConsulting\ShopifyFreeSamples\Models\SFSRule::make()])
</div>
