<div>
  <table class="auto">
    <thead>
      <tr>
        <th>Name</th>
        <th>Variants</th>
        <th>Img</th>
        <th>Eligible for sample gift</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($product_list as $product)
        <tr>
          <td>{{$product->title}}</td>
          <td>{{implode(", ", array_map(function($item){return $item["title"];}, $product->variants))}}</td>
          <td><img src="{{count($product->images) > 1 ? $product->images[0]["src"] : ""}}" alt="" style="height: 50px;"></td>
          <td><input type="checkbox" id="product_{{$product->id}}"></input></td>
          <td><button></button></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
