 @if(json_decode($cacheData->collections))
 <div class="flex w-full">
     <div class="container mx-auto px-3 xl:px-10">
         <div class="flex flex-col w-full bg-white shadow-md rounded-md rounded-b-none pb-3">
             <div class="flex items-center justify-between mx-3 mt-3">
                 <p class="relative flex h-7 items-start">
                     <a href="/collections" class="text-dark font-bold text-sm uppercase">Collections</a>
                     <span class="absolute bottom-0 bg-orange w-full h-1 z-10"></span>
                 </p>
                 <a href="/collections" class="flex uppercase font-bold text-dark transition duration-300 ease-out-expo text-sm hover:text-orange mb-2">
                     view all
                 </a>
             </div>
             <!-- Divider  -->
             <div class="flex w-full px-3">
                 <span class="flex w-full bg-light-gray" style="height: 1px; bottom:1px; margin-top:-2px;"></span>
             </div>
             <div id="collections" class="collections owl-carousel overflow-hidden grid grid-cols-3 gap-5 pt-5">
                 @foreach(json_decode($cacheData->collections) as $collection)
                 <!-- Single Collection starts here -->
                 <div class="flex flex-col justify-center items-center space-y-1">
                     <div class="flex bg-white rounded-md shadow-md mb-3 p-3 cursor-pointer">
                         <a href="/collection/{{$collection->slug}}" class="flex h-48 w-full md:w-80 lg:w-72 xl:w-80 2xl:w-96 2xl:h-60 overflow-hidden">
                             <img class="object-cover transition duration-700 ease-in-out hover:scale-125 transform-gpu" src="{{get_collection_image_url($collection->collection_image)}}" alt="{{$collection->collection_image}}">
                         </a>
                     </div>
                 </div>
                 <!-- Single Collections ends here  -->
                 @endforeach
             </div>
         </div>
     </div>
 </div>
 @endif

 <?php
    function get_collection_image_url($image)
    {

        return '/storage/collection_images/' . $image;
    }
    ?>