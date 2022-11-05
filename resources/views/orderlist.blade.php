 @extends('layouts.master')

 @section('content')
 <div class="container mx-auto">
     <div class="flex justify-center flex-wrap items-start">
         <div class="flex bg-white flex-col w-full mx-2 lg:mx-10 lg:flex-row shadow-xl rounded-md overflow-hidden top-14 right-0 transition duration-300 ease-out-expo">
             <div class="flex w-full lg:w-6/4 flex-col space-y-5 p-5">
                 <?php $package_count = 1; ?>
                 @foreach($group_items_by_seller as $key => $group)
                 <div class="transition duration-300 ease-in-out hover:shadow-lg border border-dashed border-light my-3 p-3">
                     <div class="flex items-center justify-between text-xs text-gray-600 font-light py-3 mb-5 space-x-1">
                         <p class="flex font-bold text-navy">Package {{$package_count}} of {{sizeof($group_items_by_seller)}}</p>
                         <div class="inline-block">
                             @if($max_delivery_charge_array[$key])
                             <div class="px-2 py-1 shadow-sm border border-light">
                                 <img src="{{asset('/images/icons/delivery-truck.png')}}" alt="{{asset('/images/icons/verified.svg')}}" class="hidden sm:inline-block h-6 mr-1">
                                 <p class="inline-block select-none text-xs"> Delivery Charge : <strong class="font-bold"> ৳{{$max_delivery_charge_array[$key]}}</strong></p>
                             </div>
                             @endif
                         </div>
                         <p class="inline-block items-center space-x-1">
                             Sold by : <strong class="font-bold text-navy">{{$group[0]->seller_company}}</strong>
                         </p>
                     </div>
                     <div class="flex flex-col space-y-3">
                         @foreach($group as $item)
                         <div class="block border-b border-dashed border-light"></div>
                         <div class="flex flex-col">
                             <div class="flex w-full flex-col md:flex-row">
                                 <div class="flex flex-row items-start space-x-3">
                                     <div class="flex w-40 md:w-auto">
                                         <img src="/storage/product_images/{{$item->image}}" alt="" class="h-20 w-20 object-cover">
                                     </div>
                                     <div class="flex flex-col">
                                         <p class="inline-block text-sm {{$item->delivery_charge === null ? 'line-through text-gray' : null }}">{{$item->product_variant_title ? $item->product_title .  ' - ' . $item->product_variant_title : $item->product_title}}</p>
                                         @if($item->size || $item->color)
                                         <div class="inline-block space-x-1 text-sm">
                                             @if($item->size)
                                             <p class="text-sm {{$item->delivery_charge === null ? 'line-through text-gray' : null }}">Size : {{$item->size}}
                                                 @endif
                                                 @if($item->color), Color : {{$item->color}}
                                                 @if($item->texture)
                                                 <img src="/storage/product_variant_textures/{{$item->texture}}" class="inline-block mr-1 h-5 w-5 rounded-full shadow border border-light" />
                                                 @else
                                                 <span class="inline-block h-3 w-3 rounded border border-light shadow" style="background-color:{{$item->color_code}}"></span>
                                                 @endif

                                                 @endif
                                                 @if($item->material), Material : {{$item->material}}
                                             </p>
                                             @endif
                                         </div>
                                         @endif
                                         <div class="flex space-x-3">
                                             <p class="text-sm {{$item->delivery_charge === null ? 'line-through text-gray' : null }}">Brand : {{$item->brand_name}}</p>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="flex flex-grow flex-col items-end justify-end">
                                     @if($item->delivery_charge !== null)
                                     <div class="flex space-x-2 justify-end items-start">
                                         <p class="select-none text-navy text-sm">{{$item->quantity}}</p>
                                         <p class="select-none text-navy text-xl -mt-1">×</p>
                                         @if($item->price == $item->regular_price)
                                         <p class="flex text-sm text-navy select-none flex-col items-end"> ৳ {{number_format($item->price)}} </p>
                                         @else
                                         <p class="flex text-sm text-navy select-none flex-col items-center"> ৳ {{number_format($item->price)}} <span class="text-xs text-gray"> <span class="line-through">৳ {{number_format($item->regular_price)}} </span> <span class="text-xs font-light"> ({{$item->discount_in_percentage}}%)</span> </span> </p>
                                         @endif
                                         <p class="flex text-sm font-bold select-none"> = </p>
                                         <p class="flex text-sm text-green-600 select-none"> ৳ {{number_format( ($item->price * $item->quantity))}} </p>
                                     </div>
                                     @endif
                                     @if($item->delivery_charge)
                                     <div class="mt-2 flex px-2 py-1 shadow-sm border border-light">
                                         <div class="flex items-center space-x-1">
                                             <p class="flex items-center select-none text-xs space-x-1"><span class="text-gray-600"> Delivery Charge :</span> <span class="text-sm font-light"> ৳ {{$item->delivery_charge}}</span></p>
                                         </div>
                                     </div>
                                     @elseif($item->delivery_charge === null)
                                     <div class="mt-2 flex px-2 py-1 bg-red bg-opacity-10 shadow-sm border border-red">
                                         <div class="flex items-center space-x-1">
                                             <p class="flex items-center select-none text-xs space-x-1"><span class="text-red"> This item can't be deliver outside {{$item->delivery_area}}</span> </p>
                                         </div>
                                     </div>
                                     @else
                                     <div class="mt-2 flex px-2 py-1 shadow-sm border border-light">
                                         <p class="flex items-center select-none text-sm space-x-1"> <img src="{{asset('/images/icons/verified.svg')}}" alt="{{asset('/images/icons/verified.svg')}}" class="flex h-4"> <span class="flex text-xs">Free Home Delivery</span> </p>
                                     </div>
                                     @endif
                                 </div>
                             </div>
                         </div>
                         @endforeach
                     </div>
                 </div>
                 <?php $package_count++; ?>
                 @endforeach

             </div>
             <div class="flex w-full lg:w-2/6 flex-col p-5 border-l-none md:border-l border-light space-y-3">
                 <div class="flex w-full flex-col space-y-3 items-start lg:items-end text-navy font-md text-sm">
                     <div class="flex flex-col p-5 rounded mb-3 border border-dashed border-light">
                         <div class="flex justify-end">
                             <a href="/order/shipping-address" class="inline-block"><img src="/images/icons/edit.svg" class="h-4"></a>
                         </div>
                         <div class="flex space-x-5 items-center">
                             @if($shipping_address->label =='home')
                             <img src="/images/icons/home-icon.svg" class="h-12 flex">
                             @else
                             <img src="/images/icons/building.png" class="h-12 flex">
                             @endif
                             <p class="flex flex-col text-xs w-60">
                                 <span class="flex w-full"> Address : {{$shipping_address->receiver_address}} </span>
                                 <span class="flex w-full"> Person Name : {{$shipping_address->receiver_name}} </span>
                                 <span class="flex w-full"> Contact No : {{$shipping_address->receiver_contact_no}} </span>
                                 <span class="flex w-full"> District : {{$shipping_address->district}} </span>
                             </p>
                         </div>
                     </div>

                     <p class="flex items-center">
                         Product Total Amount = ৳ {{ number_format($cart_total,2) }}
                     </p>
                     <p class="flex items-center">
                         Total Delivery Charge = ৳ {{ number_format($total_delivery_charge,2) }}
                     </p>
                     <p class="flex items-center">
                         Total Payable Amount = ৳ {{ number_format($cart_total_with_delivery,2) }}
                     </p>
                     @if($cart_total >= 5000)
                     <p class="flex flex-col items-center text-green-600 bg-green bg-opacity-20 px-2 py-1 text-xs rounded font-light">
                         <span class="font-bold"> You have to pay ৳ {{ number_format($cart_total * 0.1,2) }} security deposit money first. </span>
                         <span class="text-gray-600"> Check our 10% advance payment <a class="underline" href="/privacy-policy/#10%payment">policy.</a> </span>
                     </p>
                     @endif

                 </div>
                 @if($cart_total >= 5000)
                 <!-- <p class=" flex w-full items-center justify-center bg-navy text-white text-sm font-bold rounded py-2 cursor-pointer">
                     Pay ৳ {{ number_format($cart_total * 0.1,2) }} & Checkout
                 </p> -->
                 <p onclick="checkout()" class="checkout-button flex w-full items-center justify-center bg-navy text-white text-sm font-bold rounded py-2 cursor-pointer">
                     Checkout
                 </p>
                 @else
                 @if($cart_total)
                 <p onclick="checkout()" class="checkout-button flex w-full items-center justify-center bg-navy text-white text-sm font-bold rounded py-2 cursor-pointer">
                     Checkout
                 </p>
                 @endif
                 <p class="checkout-processing hidden flex w-full items-center justify-center bg-navy text-white text-sm font-bold rounded py-2 cursor-pointer">
                     Processing
                 </p>

                 @endif
             </div>
         </div>
     </div>
 </div>

 @endsection

 <script>
     function checkout() {
         document.getElementById('form-checkout').submit();
         document.querySelector('.checkout-button').remove();
         document.querySelector('.checkout-processing').classList.remove('hidden');
     }
 </script>