 @if(json_decode($cacheData->brands))
 <div class="flex w-full mt-5">
     <div class="container mx-auto px-3 xl:px-10">
         <div class="flex flex-col w-full bg-white shadow-md rounded-md rounded-b-none pb-3">
             <div class="flex items-center justify-between mx-3 mt-3">
                 <p class="relative flex h-7 items-start">
                     <a href="/brands" class="text-dark font-bold text-sm uppercase">Brands</a>
                     <span class="absolute bottom-0 bg-orange w-full h-1 z-10"></span>
                 </p>
                 <a href="/brands" class="flex uppercase font-bold text-dark transition duration-300 ease-out-expo text-sm hover:text-orange mb-2">
                     view all
                 </a>
             </div>
             <!-- Divider  -->
             <div class="flex w-full px-3">
                 <span class="flex w-full bg-light-gray" style="height: 1px; bottom:1px; margin-top:-2px;"></span>
             </div>
             <div class="flex">
                 <div class="flex overflow-hidden justify-center items-center h-60 w-20">
                     <p class="flex text-5xl mt-5 transform -rotate-90 font-thin tracking-wider text-dark uppercase opacity-30 select-none">BRANDS</p>
                 </div>
                 <div class="flex w-full mr-5">
                     <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-10 xl:grid-cols-12 gap-4 gap-y-6 pt-5 w-full">
                         @foreach(json_decode($cacheData->brands) as $brand)
                         <!-- Single Brand Starts Here -->
                         <div class="flex flex-col items-center space-y-1 select-none">
                             <a href="/brand/{{$brand->slug}}">
                                 <div class="flex items-center justify-center bg-white shadow transition duration-300 ease-in-out hover:shadow-xl cursor-pointer border border-light-gray p-1">
                                     <img src="{{get_brand_image_url($brand->brand_logo)}}" alt="{{$brand->brand_logo}}" class="h-16 w-16 object-contain">
                                 </div>
                             </a>
                             <p class="flex text-sm font-medium text-dark">
                                 {{$brand->brand_name}}
                         </div>
                         <!-- Single Brand Ends Here  -->
                         @endforeach
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 @endif