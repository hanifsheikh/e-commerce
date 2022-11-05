@extends('layouts.master')
@section('content')
<div class="flex w-full ">
    <div class="container mx-auto bg-white mt-0 px-3 xl:px-10 py-5">
        @if(count($products) == 1)
        <div class="w-full text-center text-md lg:text-xl font-light space-x-1 my-5"> {{count($products)}} product found in <img src="{{get_brand_image($brand->brand_logo)}}" alt="{{$brand->brand_logo}}" class="h-6 inline-flex"> <strong class="font-bold"> {{$brand->brand_name}}</strong> brand. </div>
        @elseif(count($products))
        <div class="w-full text-center text-md lg:text-xl font-light space-x-1 my-5"> {{count($products)}} products found in <img src="{{get_brand_image($brand->brand_logo)}}" alt="{{$brand->brand_logo}}" class="h-6 inline-flex"> <strong class="font-bold"> {{$brand->brand_name}} </strong> brand. </div>

        @else
        <div class="w-full text-center text-md lg:text-xl font-light space-x-1 my-5"> No products found in <img src="{{get_brand_image($brand->brand_logo)}}" alt="{{$brand->brand_logo}}" class="h-6 inline-flex"> <strong class="font-bold"> {{$brand->brand_name}} </strong> brand. </div>
        @endif
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-5">
            <!-- Single Product starts here  -->
            @foreach($products as $product)<div class="flex flex-col justify-start items-center space-y-1">
                <div class="flex flex-col items-center">
                    <a href="/product/{{$product->product_variant_url}}">
                        <div class="flex relative h-52 w-40 md:h-60 md:w-48 xl:h-60 xl:w-52 border border-light-gray bg-white rounded-md shadow hover:shadow-xl transition duration-300 ease-out-expo mb-3 p-1">
                            @if($product->minimum_offer_price)
                            <span class="absolute right-2 top-2 bg-orange shadow-lg rounded-full py-1 px-2 text-white text-xs">-{{ 100 - (intval(($product->minimum_offer_price / $product->minimum_price) * 100))  }}%</span>
                            @endif
                            <img class="flex w-full h-auto object-cover rounded" src="{{get_product_image_thumbnail_path($product->image)}}" alt="{{$product->image}}">
                        </div>
                    </a>
                    <p class="flex w-40 md:w-48 xl:w-52 text-xs xl:text-sm justify-center text-center break-word font-medium text-dark">{{short_title($product->product_title)}}</p>
                    @if($product->ratings)
                    <p class="flex space-x-1">
                        @for($i = 1 ; $i <= $product->ratings; $i++)
                            <img src="{{asset('/images/icons/star.png')}}" class="flex" style="height: 10px;">
                            @endfor
                            @for($i = $product->ratings; $i < 5 ; $i++) <img src="{{asset('/images/icons/star.png')}}" class="flex filter grayscale" style="height: 10px;">
                                @endfor
                    </p>
                    @endif
                    <div class="flex text-sm items-center font-medium space-x-1">
                        @if($product->minimum_offer_price && $product->minimum_offer_price < $product->minimum_price && $product->minimum_offer_price < $product->maximum_offer_price)
                                <p class="flex text-xs font-normal text-orange">Starts from : </p>
                                @endif
                                <p class="flex items-center space-x-2">
                                    <span class="flex text-lg text-dark">
                                        ৳ {{$product->minimum_offer_price ?  ($product->minimum_offer_price < $product->minimum_price) ? $product->minimum_offer_price : $product->minimum_price : $product->minimum_price}}
                                    </span>
                                    @if($product->minimum_offer_price && $product->minimum_offer_price < $product->minimum_price)
                                        <span class="text-gray flex text-xs line-through">৳ {{$product->minimum_price}} </span>
                                        @endif
                                </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
<?php
function get_brand_image($image)
{
    return '/storage/brand_images/' . $image;
}

function get_product_image_thumbnail_path($product_image)
{
    return '/storage/product_images/thumbnails/' . $product_image;
}

function short_title($string)
{
    $string = strip_tags($string);
    if (strlen($string) > 50) {

        // truncate string
        $stringCut = substr($string, 0, 50);
        $endPoint = strrpos($stringCut, ' ');

        //if the string doesn't contain any space then it will cut without word basis.
        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '...';
    }
    echo $string;
}
?>