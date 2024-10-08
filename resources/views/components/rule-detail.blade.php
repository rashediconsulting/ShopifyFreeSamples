<div>
    <form class="grid grid-cols-4 px-3 py-5 gap-y-2" wire:submit="storeRule()" wire:loading.class="wire-loading">
        <div class="flex items-center col-span-1">
            <select name="rule_type" id="rule_type" class="border rounded-lg border-[#6C1131] p-2" wire:model.live="rule_data.type"
                required @if (!empty($rule->id)) wire:change="saveChanges()" @endif>
                <option value="date">Date range</option>
                <option value="price">Price range</option>
            </select>
        </div>

        <div class="col-span-1 grid grid-cols-1 items-center mr-6 w-[200px]">
            <input type="{{ $rule_data['type'] }}" wire:model.live="rule_data.lower_range" name="lower_range"
                id="lower_range" class="border rounded-lg border-[#6C1131] p-2" required
                @if (!empty($rule->id)) wire:change="saveChanges()" @endif>
        </div>

        <div class="col-span-1 grid grid-cols-1 w-[200px] mr-6">
            <input type="{{ $rule_data['type'] }}" wire:model.live="rule_data.upper_range" name="upper_range"
                id="upper_range" class="border rounded-lg border-[#6C1131] p-2" required
                @if (!empty($rule->id)) wire:change="saveChanges()" @endif>
        </div>

        @if (empty($rule->id))
            <div class="grid grid-cols-1 col-span-1">
                <button type="submit" class="bg-[#6C1131] text-white uppercase p-3 text-xs rounded-full font-bold w-[200px]">Create
                    new
                    rule</button>
            </div>
        @else
            <div class="grid grid-cols-1 col-span-1">
                <button type="submit" class="bg-red-600 text-white uppercase p-3 text-xs font-bold w-[200px]">Delete
                    rule</button>
            </div>
        @endif

    </form>
</div>
