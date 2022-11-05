@extends('layouts.master')
@section('content')
<div class="flex w-full ">
    <div class="container mx-auto bg-white mt-0 px-3 xl:px-10 py-5">
        <div class="flex flex-col">
            <div class="flex flex-col space-y-5">
                <div class="flex w-full justify-start relative">
                    <img class="flex w-full h-80 object-cover" src="{{ get_banner_path($seller->banner)}}" alt="{{ get_banner_path($seller->banner)}}">
                    <div class="flex absolute flex-col bottom-0">
                        <div class="flex items-center flex-col backdrop-filter backdrop-blur-md bg-white bg-opacity-50 rounded rounded-b-none space-y-1 p-5 border border-white shadow-xl">
                            <img class="flex rounded h-40 w-40 object-cover" src="{{ get_logo_path($seller->logo)}}" alt="{{ get_logo_path($seller->logo)}}">
                            <p class="font-bold">{{$seller->company_name}}</p>
                            <p class="font-light text-sm uppercase">Positive rating : 100%</p>
                        </div>
                    </div>
                </div>
                <SellerShopProducts :seller="{{json_encode($seller)}}" />
            </div>
        </div>
    </div>
</div>
@endsection
<?php
function get_banner_path($image)
{
    return '/storage/store_banner_images/' . $image;
}
function get_logo_path($image)
{
    return '/storage/store_profile_images/' . $image;
}
?>