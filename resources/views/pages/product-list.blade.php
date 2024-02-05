@extends("app")

@section("content")

    @livewire("ShopifyFreeSamples::product-list", ["sample_set" => $sfs_set])

@endsection