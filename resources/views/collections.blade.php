@extends('layouts.master')
@section('content')
<div class="flex w-full ">
    <div class="container mx-auto bg-white mt-0 px-3 xl:px-10 py-5">
        <p class="flex w-full items-center justify-center text-xl font-bold space-x-1 my-5"> All Collections</p>
        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-3 gap-x-5 gap-y-10 py-5 px-5">
            @foreach($collections as $collection)
            <div class="flex flex-col justify-center items-center space-y-1">
                <div class="flex bg-white rounded-md shadow-md mb-3 p-3 cursor-pointer">
                    <a href="/collection/{{$collection->slug}}" class="flex h-48 w-72 md:w-80 lg:w-72 xl:w-80 2xl:w-96 2xl:h-60 overflow-hidden">
                        <img class="flex w-full object-cover transition duration-700 ease-in-out hover:scale-125 transform-gpu" src="{{get_collection_image_url($collection->collection_image)}}" alt="{{$collection->collection_image}}">
                    </a>
                </div>
                <p class="flex text-sm font-medium text-dark">
                    {{$collection->collection_title}}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
<?php

function get_collection_image_url($image)
{
    return '/storage/collection_images/' . $image;
}
?>