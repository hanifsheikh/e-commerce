@extends('layouts.master')
@section('content')
@include('layouts.hero')
@include('layouts.deals')
@include('layouts.top_categories')
@include('layouts.products_by_categories')
@include('layouts.collections')
@include('layouts.brands')


<?php # @include('layouts.best_sellers') 
?>
@include('layouts.terms_conditions')
@endsection
<?php

function get_image_path($product_image)
{
    return '/storage/product_images/' . $product_image;
}
function get_product_image_thumbnail_url($image)
{
    return '/storage/product_images/thumbnails/' . $image;
}
function get_brand_image_url($image)
{
    return '/storage/brand_images/' . $image;
}
function get_seller_image_url($image)
{
    return '/storage/seller_images/' . $image;
}
?>