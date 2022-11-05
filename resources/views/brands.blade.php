@extends('layouts.master')
@section('content')
<div class="flex w-full ">
    <div class="container mx-auto bg-white mt-0 px-3 xl:px-10 py-5">
        <p class="flex w-full items-center justify-center text-xl font-bold space-x-1 my-5"> All Brands</p>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 2xl:grid-cols-10 gap-x-5 gap-y-10 py-5 px-5">
            @foreach($brands as $brand)
            <div class="flex flex-col items-center space-y-1 select-none">
                <a href="/brand/{{$brand->slug}}">
                    <div class="flex items-center justify-center bg-white shadow transition duration-300 ease-in-out hover:shadow-xl cursor-pointer border border-light-gray p-1">
                        <img src="{{get_brand_image_url($brand->brand_logo)}}" alt="{{$brand->brand_logo}}" class="h-16 w-16 object-contain">
                    </div>
                </a>
                <p class="flex text-sm font-medium text-dark">
                    {{$brand->brand_name}}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
<?php

function get_brand_image_url($image)
{
    return '/storage/brand_images/' . $image;
}
?>