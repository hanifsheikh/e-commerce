@extends('layouts.master')
@section('content')
<div class="flex w-full ">
    <div class="container mx-auto mt-0 px-3 xl:px-10 py-5">
        <!-- Links -->
        @foreach($product->variants as $product_variant)
        <a class="hidden" href="{{env('APP_URL') . 'product/' . $product_variant->product_variant_url}}">{{env('APP_URL') . 'product/' .  $product_variant->product_variant_url}}</a>
        @endforeach
        <!-- Links Ends Here  -->

        <Product-View :user_id="{{$user_id}}" :product="{{$product}}" :related_products="{{$related_products}}" :reviews="{{$customer_reviews}}">
    </div>
</div>
@endsection