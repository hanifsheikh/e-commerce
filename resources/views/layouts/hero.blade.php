    <!-- Slider & Recommended Products Area Starts here  -->
    <div id="slider" class="flex w-full mt-0 lg:mt-3">
        <div class="container mx-auto px-3 xl:px-10">
            <div class="flex mt-3 md:mt-0 flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-4 lg:space-y-0">
                @if(count($banners))
                <div id="single-slide" class="flex w-full lg:w-3/4 offer-banners owl-carousel owl-theme rounded-none rounded md:rounded-md overflow-hidden shadow-md">
                    @foreach($banners as $banner)
                    <div class="item flex">
                        @if($banner->url)
                        <a href="{{$banner->url}}" class="flex w-full">
                            <img class="object-cover sm:h-80 md:h-slider-md lg:h-slider-lg xl:h-slider w-full" src="{{get_banner_image_url($banner->image)}}" alt="{{$banner->image}}">
                        </a>
                        @else
                        <img class="object-cover sm:h-80 md:h-slider-md lg:h-slider-lg xl:h-slider w-full" src="{{get_banner_image_url($banner->image)}}" alt="{{$banner->image}}">
                        @endif
                    </div>
                    @endforeach

                </div>
                @endif

                @if(json_decode($cacheData->recommended_products))
                <div class="flex justify-center">
                    <div class="grid grid-cols-4 lg:grid-cols-2 gap-3 lg:gap-4">
                        @foreach(json_decode($cacheData->recommended_products) as $recommended_product)
                        <div class="flex rounded-md overflow-hidden shadow-md">
                            <a href="{{$recommended_product->link}}">
                                <img src="{{get_recommended_product_image_url($recommended_product->image)}}" alt="{{$recommended_product->image}}" class="object-cover lg:h-32 lg:w-32 xl:h-52 xl:w-52">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Slider & Recommended Products Area Ends here  -->

    <?php
    function get_banner_image_url($image)
    {
        return ' /storage/banners/' . $image;
    }
    function get_recommended_product_image_url($image)
    {
        return ' /storage/recommended_product_images/' . $image;
    }
    ?>