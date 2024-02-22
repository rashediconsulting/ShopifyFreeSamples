<div class="p-5 mt-3 w-full" wire:loading.class="wire-loading">
    <h6>If you don't apply any rule, this sample set will be applied to every cart</h6>
    <div class="table-auto mt-5 bg-white w-full">
        <div class="grid grid-cols-4 table-title" @if ($sfs_set->rules->isNotEmpty()) style="display: none;" @endif>
            <div class="text-start font-bold">Type</div>
            <div rowspan="2" class="text-start font-bold">From</div>
            <div class="text-start font-bold">To</div>
        </div>
        <button wire:click="refreshSampleSetRules()">Button</button>

        <!-- Check if rules are present -->
        @if ($sfs_set->rules->isNotEmpty())
            <div>

                <h2 class="font-bold text-[#6C1131] mb-3 text-lg ">Existing rules</h2>
                <div class="grid grid-cols-4">
                    <div class="text-start font-bold">Type</div>
                    <div rowspan="2" class="text-start font-bold">From</div>
                    <div class="text-start font-bold">To</div>
                </div>
                @foreach ($sfs_set->rules as $rule)
                    @livewire('ShopifyFreeSamples::rule-detail', ['sfs_set_id' => $sfs_set->id, 'rule' => $rule], key($rule->id))
                @endforeach
            </div>
        @endif
        @if ($sfs_set->rules->isEmpty())
            <div>

                @livewire('ShopifyFreeSamples::rule-detail', ['sfs_set_id' => $sfs_set->id, 'rule' => RashediConsulting\ShopifyFreeSamples\Models\SFSRule::make()], key(rand()))
            </div>
        @else
            <div>
                <h2 class="font-bold text-[#6C1131] mb-3 text-lg ">Create a new rule</h2>
                <div class="grid grid-cols-4">
                    <div class="text-start font-bold">Type</div>
                    <div rowspan="2" class="text-start font-bold">From</div>
                    <div class="text-start font-bold">To</div>
                </div>
                @livewire('ShopifyFreeSamples::rule-detail', ['sfs_set_id' => $sfs_set->id, 'rule' => RashediConsulting\ShopifyFreeSamples\Models\SFSRule::make()], key(rand()))
            </div>
        @endif
    </div>
</div>
