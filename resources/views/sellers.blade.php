@extends('layouts.master')
@section('content')
<div class="flex w-full ">
    <div class="container mx-auto bg-white mt-0 px-3 xl:px-10 py-5">
        <p class="flex w-full items-center justify-center text-xl font-bold space-x-1 my-5"> All Sellers</p>
        <div class="grid grid-cols-2 grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 2xl:grid-cols-5 gap-x-5 gap-y-10 py-5 px-5">
            @foreach($sellers as $seller)
            <!-- Single Seller -->
            <div class="flex flex-col items-center space-y-3">
                <div class="flex p-1 bg-white h-32 w-32 shadow-md rounded overflow-hidden">
                    <img class="flex object-cover rounded" src="{{get_store_image_url($seller->logo)}}" alt="{{$seller->logo}}">
                </div>
                <div class="flex flex-col items-center space-y-2">
                    <p class="block uppercase text-center break-word font-medium text-xs text-dark">{{$seller->company_name}}</p>

                    <div class="flex">
                        <a href="/shop/{{$seller->shop_slug}}" class="inline-block font-medium text-orange bg-orange hover:text-white hover:bg-opacity-80 transition duration-300 ease-out-expo bg-opacity-20 rounded px-3 py-1 justify-center items-center space-x-1">
                            <span class="inline-block text-xs select-none">Visit Store</span>
                            <img class="inline-block select-none" src="/images/icons/arrow-left.png" style="transform: rotate(180deg);height:15px;">
                        </a>
                    </div>
                </div>
            </div>
            <!-- Single Seller Ends  -->
            @endforeach
        </div>
    </div>
</div>

@endsection
<?php

function get_store_image_url($image)
{
    return '/storage/store_profile_images/' . $image;
}
?>