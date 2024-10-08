<div class="p-5 ml-[400px] mt-3 w-full">
    <div>
        @foreach ($messages as $m)
            <p>{{ $m }}</p>
        @endforeach
    </div>
    <div class="flex flex-col items-center justify-center gap-10 p-5 mt-3 mb-5 bg-white rounded-lg shadow">
        <div class="flex flex-col items-end justify-end mt-10">
            <h2 class="font-extrabold text-[#6C1131] mb-3 text-4xl">Sample sets</h2>
        </div>
        <div class="w-[90%] items-end flex flex-col">
            <button type="button"
                class="bg-[#6C1131] rounded-full shadow hover:shadow-lg w-[150px] text-white uppercase p-3 text-xs font-bold"
                wire:click="createSampleSet()">Create new set</button>
        </div>
        <div class="p-5 w-[90%] bg-[#F0F0F0] rounded-xl mb-8 shadow-lg flex flex-col items-center justify-center">
            <div class="flex flex-col  items-center w-full px-10 mt-10 py-3 bg-[#F0F0F0]  table-auto">
                <div class="grid border border-gray-200 rounded-t-lg w-full gap-4 px-3 py-3 mt-3 bg-[#d6d6d6] grid-samples">
                    <div class="font-bold text-start">Name</div>
                    <div class="font-bold text-start">Rules</div>
                    <div class="font-bold text-start">Active</div>
                    <div class="font-bold text-start">Actions</div>
                </div>
                @foreach ($sample_set_list as $sample_set)
                    <div class="grid border last:rounded-b-lg items-center border-gray-200 px-3 w-full gap-4 bg-[#f5f5f5] py-5 grid-samples">
                        <div>
                            <div class="font-bold">{{ $sample_set->name }}</div>
                        </div>
                        <div class="w-full">
                            @if ($sample_set->rules->isEmpty())
                                <div>-</div>
                            @else
                                @foreach ($sample_set->rules as $rule)
                                    <div>{{ $rule->short_description }}</div>
                                @endforeach
                            @endif
                        </div>
                        <div class="font-bold text-start">
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
                        <div class="flex gap-4">
                            <button type="button"
                                class="bg-[#6C1131] shadow max-h-[40px] rounded-full text-white uppercase p-3 px-5 text-xs font-bold"
                                wire:click="editSampleSet({{ $sample_set->id }})"><i class="fas fa-pencil-alt"></i>Edit set</button>
                            <button type="button"
                                class="bg-red-600 shadow text-white rounded-full max-h-[40px] uppercase p-3  px-5 text-xs font-bold" x-data
                                @click="$dispatch('open-delete-modal', {{ $sample_set->id }})">Delete set</button>
                        </div>
                        <div x-data="{ open: false, sampleSetId: null }"
                            @open-delete-modal.window="open = true; sampleSetId = $event.detail;">
                            <div x-show="open"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-75">
                                <div class="w-1/3 p-8 bg-white rounded shadow-lg">
                                    <h2 class="text-2xl font-bold text-[#6C1131] mb-4">Confirm Deletion</h2>
                                    <p class="mb-6">Are you sure you want to delete this sample set?</p>
                                    <div class="flex justify-end gap-4">
                                        <button type="button"
                                            class="px-4 py-2 font-bold text-gray-800 bg-gray-300 rounded hover:bg-gray-400"
                                            @click="open = false">Cancel</button>
                                        <button type="button"
                                            class="px-4 py-2 font-bold text-white bg-red-600 rounded hover:bg-red-700"
                                            wire:click="deleteSampleSet({{ $sample_set->id }}); open = false">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
