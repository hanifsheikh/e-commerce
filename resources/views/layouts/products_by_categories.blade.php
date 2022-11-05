@if(json_decode($cacheData->category_products))
<div class="flex w-full">
    <div class="container mx-auto px-3 xl:px-10">
        <!-- Card starts  -->
        @foreach(json_decode($cacheData->category_products) as $category_id => $products)
        <div class="flex flex-col w-full bg-white shadow-md rounded-md rounded-b-none pb-3 my-5">
            <div class="flex items-center justify-between mx-3 mt-3">
                <p class="relative flex h-7 items-start">
                    <a href="/category/{{$products[0]->category_url}}" class="text-dark font-bold text-sm uppercase">{{$products[0]->category_name}}</a>
                    <span class="absolute bottom-0 bg-orange w-full h-1 z-10"></span>
                </p>
                <a href="/category/{{$products[0]->category_url}}" class="flex uppercase font-bold text-dark transition duration-300 ease-out-expo text-sm hover:text-orange mb-2">
                    view all
                </a>
            </div>
            <!-- Divider  -->
            <div class="flex w-full px-3">
                <span class="flex w-full bg-light-gray" style="height: 1px; bottom:1px; margin-top:-2px;"></span>
            </div>
            <div id="product_by_categories" class="product_by_categories owl-carousel overflow-hidden grid grid-cols-5 gap-12 pt-5">

                <!-- Single Product starts here  -->
                @foreach($products as $product)<div class="flex flex-col justify-center items-center space-y-1">
                    <div class="flex flex-col items-center">
                        <a href="product/{{$product->product_variant_url}}">
                            <div class="relative flex h-48 w-40 md:h-52 md:w-48 xl:h-52 xl:w-52 border border-light-gray bg-white rounded-md shadow hover:shadow-xl transition duration-300 ease-out-expo mb-3 p-1">
                                @if($product->minimum_offer_price)
                                <span class="absolute right-2 shadow-lg top-2 bg-orange rounded-full py-1 px-2 text-white text-xs">-{{ 100 - (intval(($product->minimum_offer_price / $product->minimum_price) * 100))  }}%</span>
                                @endif
                                <img class="object-contain rounded" src="{{get_product_image_thumbnail_url($product->image)}}" alt="{{$product->image}}">
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
        @endforeach
    </div>

</div>
@endif