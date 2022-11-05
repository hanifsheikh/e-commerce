    <!-- Deals & Best Selling Starts here  -->
    <div class="flex w-full mt-5 md:mt-3">
        <div class="container mx-auto px-3 xl:px-10">
            <div class="grid grid-cols-12 gap-3 xl:gap-y-0">
                <div class="col-span-12 lg:col-span-6 xl:col-span-4">
                    @if(json_decode($cacheData->daily_deals))
                    <!--  Deals of The Day Card starts  -->
                    <div class="flex h-full flex-col bg-white shadow-md rounded-md rounded-b-none">
                        <div class="flex ml-3 mt-3">
                            <p class="relative flex h-7 items-start">
                                <a href="" class="text-dark font-bold text-sm uppercase">Deals of the day</a>
                                <span class="absolute bottom-0 bg-orange w-full h-1 z-5"></span>
                            </p>
                        </div>
                        <!-- Divider  -->
                        <div class="flex w-full px-3">
                            <span class=" flex w-full bg-light-gray" style="height: 1px; bottom:1px; margin-top:-2px;"></span>
                        </div>
                        <div id="deals-of-the-day" class="h-full deals-of-the-day owl-carousel overflow-hidden">
                            <?php $product_count = 1; ?>
                            @foreach(json_decode($cacheData->daily_deals) as $product)
                            <!-- Single Deals of the Day Product Starts Here -->
                            <div class="grid grid-cols-2 gap-2 justify-between" style="padding:2rem 2rem;">
                                <div class="col-span-1">
                                    <p class="block text-xs font-medium text-dark uppercase"> {{ sprintf("%02d", $product_count) . ". " .  $product->product_title}}</p>
                                    @if($product->ratings)
                                    <p class="block mt-1">
                                        @for($i = 1 ; $i <= $product->ratings; $i++)
                                            <img src="{{asset('/images/icons/star.png')}}" style="display:inline-block; width: 14px; margin-right:5px">
                                            @endfor
                                            @for($i = $product->ratings; $i < 5 ; $i++) <img src="{{asset('/images/icons/star.png')}}" class="filter grayscale" style="display:inline-block; width: 14px; margin-right:5px">
                                                @endfor
                                    </p>
                                    @endif
                                    <p class="block mt-1">
                                    <div class="flex items-center space-x-2">
                                        @if($product->minimum_offer_price && $product->minimum_offer_price < $product->minimum_price && $product->minimum_offer_price < $product->maximum_offer_price)
                                                <p class="flex text-xs font-normal text-gray">Starts from : </p>
                                                @endif
                                                <p class="flex text-lg font-medium text-orange ">
                                                    ৳ {{$product->minimum_offer_price ?  ($product->minimum_offer_price < $product->minimum_price) ? $product->minimum_offer_price : $product->minimum_price : $product->minimum_price}}
                                                </p>
                                                @if($product->minimum_offer_price && $product->minimum_offer_price < $product->minimum_price)
                                                    <span class="text-gray flex text-xs line-through">৳ {{$product->minimum_price}} </span>
                                                    @endif
                                    </div>
                                    </p>
                                    <div class="flex space-x-3 mt-1 items-center">
                                        <a href="/product/{{$product->product_variant_url}}" class="inline-block text-xs font-bold bg-dark py-2 px-4 rounded-full text-white  uppercase">
                                            View product
                                        </a>
                                    </div>
                                </div>
                                <div class="col-span-1 grid justify-items-end">
                                    <a href="/product/{{$product->product_variant_url}}" class="relative">
                                        @if($product->minimum_offer_price)
                                        <span class="absolute right-2 top-2 bg-orange shadow-lg rounded-full py-1 px-2 text-white text-xs">-{{ 100 - (intval(($product->minimum_offer_price / $product->minimum_price) * 100))  }}%</span>
                                        @endif
                                        <img class="rounded w-full h-auto object-contain shadow-lg" src="{{get_product_image_thumbnail_path($product->image)}}" alt="{{$product->image}}" style="height: 10rem; width:10rem;">
                                    </a>
                                </div>
                            </div>
                            <?php $product_count++ ?>
                            <!-- Single Deals of the Day Product Ends Here  -->
                            @endforeach

                        </div>
                    </div>
                    <!-- Deals of The Day Card Ends  -->
                    @endif
                </div>

                <!-- Best Selling Area Starts Here  -->
                <!-- For XL Viewport  -->
                <div class="col-span-8 hidden xl:block">
                    @include('includes.xl.best_selling')
                </div>
                <!-- For Large Viewport  -->
                <div class="col-span-6 hidden lg:block xl:hidden">
                    @include('includes.large.best_selling')
                </div>

                <!-- For Medium Viewport -->
                <div class="col-span-12 hidden md:block lg:hidden">
                    @include('includes.md.best_selling')
                </div>

                <!-- For Small Viewport -->
                <div class="col-span-12 block md:hidden">
                    @include('includes.sm.best_selling')
                </div>
                <!-- Best Selling Area Ends Here  -->
            </div>
        </div>
    </div>


    <!-- Deals & Best Selling Ends here  -->


    <?php

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