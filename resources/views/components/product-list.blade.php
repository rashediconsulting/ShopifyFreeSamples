<div class="p-5 ml-[400px] mt-3  w-full">

  <div>
    @foreach($messages as $m)
      <p>{{$m}}</p>
    @endforeach

  </div>
  <div class=" items-center bg-white rounded mt-3 flex justify-around gap-10 p-5 mb-5 shadow">
    {{-- <div class="flex gap-3">
      <label class="text-[#6C1131] font-bold" for="name">Name</label>
      <input type="text" wire:model="name" name="name" id="name" class="w-[150px]">
    </div> --}}
    <div class="flex gap-3 group">
      <label class="text-[#6C1131] font-bold" for="active">Active</label>
      <input type="checkbox" wire:model="active" name="active" id="active" value="active">
      <span class="absolute p-2 px-1  w-[200px] m-4 mx-auto text-sm text-gray-100 transition-opacity bg-gray-800 rounded-md opacity-0 group-hover:opacity-100 top-[8px] " id="priceInfo">Activate the samples on live</span>
    </div>
    <div class="flex gap-3 group">
      <label class="text-[#6C1131] font-bold" for="quantity">Quantity</label>
      <input type="number" wire:model="quantity" name="quantity" id="quantity" class="w-[40px]">
       <span class="absolute p-2 px-1  w-[200px] m-4 mx-auto text-sm text-gray-100 transition-opacity bg-gray-800 rounded-md opacity-0 group-hover:opacity-100 top-[10px] " id="priceInfo">Quantity of samples added</span>
    </div>
    <div class="flex gap-3 group">
      <label class="text-[#6C1131] font-bold" for="display_in_checkout">Display in checkout</label>
      <input type="checkbox" wire:model="display_in_checkout" name="display_in_checkout" id="display_in_checkout" value="display_in_checkout">
       <span class="absolute p-2 px-1  w-[200px] m-4 mx-auto text-sm text-gray-100 transition-opacity bg-gray-800 rounded-md opacity-0 group-hover:opacity-100 top-[-15px] " id="priceInfo">Activate if you want the samples to show on checkout</span>
    </div>
    <div class="flex gap-3 group">
      <label class="text-[#6C1131] font-bold" for="repeatable">Repeatable</label>
      <input type="checkbox" wire:model="repeatable" name="repeatable" id="repeatable" value="repeatable">
      <span class="absolute p-2 px-1  w-[200px] m-4 mx-auto text-sm text-gray-100 transition-opacity bg-gray-800 rounded-md opacity-0 group-hover:opacity-100 top-[-25px] " id="priceInfo">Do you want to repeat the samples if there is not enough to fill the quantity?</span>
    </div>
      <div>
        <button class="bg-[#6C1131] text-white uppercase p-3 text-xs font-bold " wire:click="saveChanges()">Save changes</button>
      </div>

  </div>


  <div class="p-5 bg-white mb-8 shadow">
    <h2 class="font-bold text-[#6C1131] mb-3 text-xl ">Products included</h2>
    <div class="table-auto bg-white w-full">
      <div class="grid grid-cols-4">
        <div class="text-start font-bold">Name</div>
        <div rowspan="2" class="text-start font-bold">Variants</div>
        <div class="text-start font-bold">Img</div>
        <div class="text-start font-bold">Actions</div>
      </div>
   

      @foreach($free_sample_list as $product)
        <div class="  grid grid-cols-4 py-3 ">
          <div>{{$product["title"]}}</div>
          <div>{{implode(", ", array_map(function($item){return $item["title"];}, $product["variants"]))}}</div>
          <div ><img src="{{count($product["images"]) > 1 ? $product["images"][0]["src"] : ""}}" alt="" style="height: 50px;"></div>
          <div><button class="bg-gray-500 w-[180px] text-white uppercase p-3 text-xs font-bold " wire:click="removeProductFromFreeSample({{$product["id"]}})">Remove from samples</button></div>
        </div>
      @endforeach
  
    </div>

    <h2 class="font-bold text-[#6C1131] mb-3 text-xl ">Products</h2>
    <div class=" bg-white w-full">
      <div class="grid grid-cols-4">
        <div class="text-start font-bold">Name</div>
        <div class="text-start font-bold">Variants</div>
        <div class="text-start font-bold">Img</div>
        <div class="text-start font-bold">Actions</div>
      </div>
      @foreach($product_list as $product)
        <div class=" py-5 gap-y-2 mb-10 grid grid-cols-4">
          <div>{{$product["title"]}}</div>
          <div>{{implode(", ", array_map(function($item){return $item["title"];}, $product["variants"]))}}</div>
          <div><img src="{{count($product["images"]) > 1 ? $product["images"][0]["src"] : ""}}" alt="" style="height: 50px;"></div>
          <div><button class="bg-[#6C1131] w-[180px] text-white uppercase p-3 text-xs font-bold " wire:click="addProductAsFreeSample({{$product["id"]}})">Add to samples</button></div>
        </div>
      @endforeach
    </div>
  </div>
</div>


