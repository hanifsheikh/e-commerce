@if(json_decode($cacheData->best_selling))
<div class="hidden lg:flex xl:hidden flex-col bg-white shadow-md rounded-md rounded-b-none">
    <div class="flex ml-3 pt-3">
        <p class="relative flex h-7 items-start">
            <!-- <a href="" class="text-dark font-bold text-sm uppercase">BEST Selling</a> -->
            <a href="" class="text-dark font-bold text-sm uppercase">Latest Products</a>
            <span class="absolute bottom-0 bg-paste w-full h-1 z-5"></span>
        </p>
    </div>
    <!-- Divider  -->
    <div class="flex w-full px-3">
        <span class=" flex w-full bg-light-gray" style="height: 1px; bottom:1px; margin-top:-2px;"></span>
    </div>
    <div id="best-selling" class="best-selling owl-carousel overflow-hidden">

        <?php $group_counter = 0;
        $products_count = count(json_decode($cacheData->best_selling)); ?>
        @foreach(json_decode($cacheData->best_selling) as $product)
        @if($group_counter % 2 ==0 )
        <!-- Single Group Starts Here -->
        <div class="grid grid-cols-1 gap-2 gap-y-8" style="padding:2rem 2rem;">
            @endif
            <!-- Single Product  -->
            <div class="grid grid-cols-5">
                <a class="flex col-span-1" href="/product/{{$product->product_variant_url}}" style="height:4rem; width:4rem;">
                    <img class="flex rounded shadow-md w-full h-full" src="{{get_product_image_thumbnail_path($product->image)}}" alt="{{$product->image}}" style="object-fit:contain;">
                </a>
                <div class="flex col-span-4 flex-col space-y-2">
                    <a href="/product/{{$product->product_variant_url}}" class="flex uppercase font-medium text-xs text-dark"> {{short_title($product->product_title)}} </a>

                    <p class="flex space-x-1 mt-1">
                        @for($i = 1 ; $i <= $product->ratings; $i++)
                            <img src="{{asset('/images/icons/star.png')}}" style="display:inline-block; width: 12px; margin-right:3px">
                            @endfor
                            @for($i = $product->ratings; $i < 5 ; $i++) <img src="{{asset('/images/icons/star.png')}}" class="filter grayscale" style="display:inline-block; width: 12px; margin-right:3px">
                                @endfor

                    </p>

                    <p class="flex font-medium text-xs text-paste space-x-1">
                        @if($product->minimum_offer_price && $product->minimum_offer_price < $product->minimum_price && $product->minimum_offer_price < $product->maximum_offer_price)
                                <span class="flex font-normal text-gray">Starts from : </span>
                                @endif
                                <span class="flex">
                                    ৳ {{$product->minimum_offer_price ?  ($product->minimum_offer_price < $product->minimum_price) ? $product->minimum_offer_price : $product->minimum_price : $product->minimum_price}}
                                </span>
                                @if($product->minimum_offer_price && $product->minimum_offer_price < $product->minimum_price)
                                    <span class="text-gray flex line-through">৳ {{$product->minimum_price}} </span>
                                    @endif

                    </p>
                </div>
            </div>
            <!-- Single Product Ends Here  -->
            <?php $group_counter++ ?>
            @if($group_counter % 2 ==0 || $group_counter == $products_count )
        </div>
        @endif
        <!-- Single Group Ends Here  -->
        @endforeach
    </div>
</div>

@endif