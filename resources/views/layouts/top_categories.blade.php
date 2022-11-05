<div class="flex w-full mt-3 mb-14">
    <div class="container mx-auto px-3 xl:px-10">
        <div class="flex justify-between py-10">
            <p class="flex uppercase font-bold text-md flex-grow items-center justify-start">
                <span class="flex">SHOP BY CATEGORIES</span>
            </p>
            <!-- <a style="text-underline-offset: 2px; text-decoration-thickness: 3px;" href="/categories" class="flex uppercase hover:text-orange text-dark transition duration-300 ease-out-expo underline font-bold text-sm hover:decoration-red">
                view all
            </a> -->
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-6 gap-6 gap-y-16">
            @foreach($categories as $parent)
            <div class="flex flex-col items-center space-y-3">
                <a href="/category/{{$parent->category_url}}">
                    <div class="flex items-center justify-center bg-white rounded-full shadow transition duration-300 ease-in-out hover:shadow-xl cursor-pointer border border-light-gray p-7">
                        <img src="{{get_category_thumbnail_image_url($parent->category_thumbnail)}}" class="h-16 w-16 object-cover">
                    </div>
                </a>
                <p class="flex text-sm font-medium text-dark">
                    {{$parent->category_name}}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>
</div>
<?php

function get_category_thumbnail_image_url($image)
{
    return '/storage/category_images/thumbnails/' . $image;
}
?>