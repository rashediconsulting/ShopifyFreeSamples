<div class="p-5 ml-[400px] mt-3  w-full">

  <div>
    @foreach($messages as $m)
    <p>{{$m}}</p>
    @endforeach

  </div>
  <div class=" items-center bg-white rounded mt-3 flex justify-around gap-10 p-5 mb-5 shadow">
    <div class="p-5 bg-white mb-8 shadow">
      <h2 class="font-bold text-[#6C1131] mb-3 text-xl ">Sample sets</h2>
      <div class="table-auto bg-white w-full">

        <div class="grid grid-cols-4">
          <div class="text-start font-bold">Name</div>
          <div class="text-start font-bold">Rules</div>
        </div>

        @foreach($sample_set_list as $sample_set)
        <div class="py-5 gap-y-2 mb-10 grid grid-cols-4">

          <div class="col-span-1">
            <div>{{$sample_set->name}}</div>
          </div>

          <div class="col-span-3 grid grid-cols-3">
            @foreach($sample_set->rules as $rule)
              <div>{{$rule->short_description}}</div>
            @endforeach
          </div>

          <div class="col-span-1"><a href="{{route('free-sample-set-detail', $sample_set)}}">Edit</a></div>

        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>


