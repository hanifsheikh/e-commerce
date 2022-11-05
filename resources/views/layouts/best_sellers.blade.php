<?php
function short_company_name($string)
{
    $string = strip_tags($string);
    if (strlen($string) > 20) {

        // truncate string
        $stringCut = substr($string, 0, 20);
        $endPoint = strrpos($stringCut, ' ');

        //if the string doesn't contain any space then it will cut without word basis.
        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '...';
    }
    echo $string;
}
?>
@if(json_decode($cacheData->sellers))
<div class="flex w-full mt-5">
    <div class="container mx-auto px-3 xl:px-10">
        <div class="flex flex-col w-full bg-white shadow-md rounded-md rounded-b-none pb-3">
            <div class="flex items-center justify-between mx-3 mt-3">
                <p class="relative flex h-7 items-start">
                    <a href="" class="text-dark font-bold text-sm uppercase">Best Sellers</a>
                    <span class="absolute bottom-0 bg-orange w-full h-1 z-10"></span>
                </p>
                <a href="/sellers" class="flex uppercase font-bold text-dark transition duration-300 ease-out-expo text-sm hover:text-orange mb-2">
                    view all
                </a>
            </div>
            <!-- Divider  -->
            <div class="flex w-full px-3">
                <span class="flex w-full bg-light-gray" style="height: 1px; bottom:1px; margin-top:-2px;"></span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 2xl:grid-cols-5 gap-x-5 gap-y-10 py-5 px-5">
                @foreach(json_decode($cacheData->sellers) as $seller)
                <!-- Single Seller -->
                <div class="flex space-x-3 items-center">
                    <div class="flex p-1 bg-white h-20 w-20 shadow-md rounded overflow-hidden">
                        <img class="flex object-cover rounded" src="{{get_store_image_url($seller->logo)}}" alt="{{$seller->logo}}">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <p class="flex uppercase break-word font-medium text-xxs text-dark">{{short_company_name($seller->company_name)}}</p>
                        <p class="flex space-x-1 items-center">
                            <img src="{{asset('/images/icons/star.png')}}" style="display:inline-block; width: 12px; margin-right:3px">
                            <img src="{{asset('/images/icons/star.png')}}" style="display:inline-block; width: 12px; margin-right:3px">
                            <img src="{{asset('/images/icons/star.png')}}" style="display:inline-block; width: 12px; margin-right:3px">
                            <img src="{{asset('/images/icons/star.png')}}" style="display:inline-block; width: 12px; margin-right:3px">
                        </p>
                        <div class="flex">
                            <a href="/shop/{{$seller->shop_slug}}" class="inline-block font-medium text-orange bg-orange hover:text-white hover:bg-opacity-80 transition duration-300 ease-out-expo bg-opacity-20 rounded px-3 py-1 justify-center items-center space-x-1">
                                <span class="inline-block text-xxs select-none">Visit Store</span>
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
</div>
@endif

<?php
function get_store_image_url($image)
{
    return '/storage/store_profile_images/' . $image;
}
?>