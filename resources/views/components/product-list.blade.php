<div>

  <div>
    @foreach($messages as $m)
      <p>{{$m}}</p>
    @endforeach

  </div>
  <div class="m-5">
    <label for="name">name</label>
    <input type="text" wire:model="name" name="name" id="name">
    <br>
    <label for="active">active</label>
    <input type="checkbox" wire:model="active" name="active" id="active" value="active">
    <br>
    <label for="quantity">quantity</label>
    <input type="number" wire:model="quantity" name="quantity" id="quantity">
    <br>
    <label for="display_in_checkout">display_in_checkout</label>
    <input type="checkbox" wire:model="display_in_checkout" name="display_in_checkout" id="display_in_checkout" value="display_in_checkout">
    <br>
    <label for="repeatable">repeatable</label>
    <input type="checkbox" wire:model="repeatable" name="repeatable" id="repeatable" value="repeatable">
    <br>
  </div>

  <div>
    <button wire:click="saveChanges()">Save changes</button>
  </div>

  <table class="table-auto">
    <thead>
      <tr>
        <th>Name</th>
        <th>Variants</th>
        <th>Img</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($free_sample_list as $product)
        <tr>
          <td>{{$product["title"]}}</td>
          <td>{{implode(", ", array_map(function($item){return $item["title"];}, $product["variants"]))}}</td>
          <td><img src="{{count($product["images"]) > 1 ? $product["images"][0]["src"] : ""}}" alt="" style="height: 50px;"></td>
          <td><button wire:click="removeProductFromFreeSample({{$product["id"]}})">Remove from samples</button></td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <table class="table-auto">
    <thead>
      <tr>
        <th>Name</th>
        <th>Variants</th>
        <th>Img</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($product_list as $product)
        <tr>
          <td>{{$product["title"]}}</td>
          <td>{{implode(", ", array_map(function($item){return $item["title"];}, $product["variants"]))}}</td>
          <td><img src="{{count($product["images"]) > 1 ? $product["images"][0]["src"] : ""}}" alt="" style="height: 50px;"></td>
          <td><button wire:click="addProductAsFreeSample({{$product["id"]}})">Add to samples</button></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
