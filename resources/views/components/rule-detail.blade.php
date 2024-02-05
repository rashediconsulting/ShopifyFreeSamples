<form class="py-5 gap-y-2 mb-10 grid grid-cols-4" wire:submit="storeRule()" wire:key="rule-{{$rule->id ?: "new"}}">
  @dump($rule->short_description)

  <div class="col-span-1">
    <select name="rule_type" id="rule_type" wire:model="rule_data.type" required @if(!empty($rule->id)) wire:change="saveChanges()" @endif>
      <option value="date">Date range</option>
      <option value="number">Price range</option>
    </select>
  </div>

  <div class="col-span-1 grid grid-cols-1">
      <input type="{{$rule->type}}" wire:model="rule_data.lower_range" name="lower_range" id="lower_range" class="border border-black" required @if(!empty($rule->id)) wire:change="saveChanges()" @endif>
  </div>

  <div class="col-span-1 grid grid-cols-1">
    <input type="{{$rule->type}}" wire:model="rule_data.upper_range" name="upper_range" id="upper_range" class="border border-black" required @if(!empty($rule->id)) wire:change="saveChanges()" @endif>
  </div>

  @if(empty($rule->id))
    <div class="col-span-1 grid grid-cols-1">
      <button type="submit" class="bg-[#6C1131] text-white uppercase p-3 text-xs font-bold">Create new rule</button>
    </div>
  @else
    <div class="col-span-1 grid grid-cols-1">
      <button type="submit" class="bg-[#6C1131] text-white uppercase p-3 text-xs font-bold">Delete rule</button>
    </div>
  @endif

</form>