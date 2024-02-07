<div class="p-5 ml-[400px] mt-3  w-full">

    <div>
        @foreach ($messages as $m)
            <p>{{ $m }}</p>
        @endforeach

    </div>
    <div class=" items-center h-screen bg-white rounded mt-3 flex justify-around gap-10 p-5 mb-5 shadow">
        <div class="p-5 bg-white mb-8 shadow flex flex-col items-center justify-center">
            <h2 class="font-bold text-[#6C1131] mb-3 text-xl ">Sample sets</h2>
            <button type="button" class="bg-[#6C1131] text-white uppercase p-3 text-xs font-bold"
                wire:click="createSampleSet()">Create new set</button>
            <div class="table-auto flex flex-col  items-center bg-white w-full">

                <div class="grid grid-cols-4 mt-3 w-full gap-4">
                    <div class="text-start font-bold">Name</div>
                    <div class="text-start font-bold">Rules</div>
                    <div class="text-start font-bold">Active</div>
                    <div class="text-start font-bold">Actions</div>
                </div>

                @foreach ($sample_set_list as $sample_set)
                    <div class="py-5 gap-y-2 mb-10 grid grid-cols-4 gap-4 ">

                        <div class="">
                            <div>{{ $sample_set->name }}</div>
                        </div>

                        <div class=" ">
                            @foreach ($sample_set->rules as $rule)
                                <div>{{ $rule->short_description }}</div>
                            @endforeach
                        </div>

                        <div class="text-start font-bold">
                            @if ($sample_set->active)
                                <span><svg class="w-4" fill="green" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 448 512">
                                        <path
                                            d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" />
                                    </svg></span>
                            @else
                                <span><svg class="w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"
                                        fill="red">
                                        <path
                                            d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                    </svg></span>
                            @endif
                        </div>
                        <div class="flex flex-col gap-4">
                            <button type="button" class="bg-[#6C1131] text-white uppercase p-3 text-xs font-bold"
                                wire:click="editSampleSet({{ $sample_set->id }})">Edit set</button>
                            <button type="button" class="bg-[#6C1131] text-white uppercase p-3 text-xs font-bold"
                                wire:click="deleteSampleSet({{ $sample_set->id }})">Delete set</button>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
