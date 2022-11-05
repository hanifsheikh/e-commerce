 <div class="fixed backdrop-filter shadow z-40 backdrop-blur-lg w-full bg-white bg-opacity-80 items-center px-5 top-0">
     <div class="flex w-full justify-between items-center">
         <div class="flex space-x-10">
             <div class="flex justify-start items-center h-14">
                 <a href="{{url('/')}}">
                     <img src="/images/logo.png" class="h-7 mx-auto" alt="">
                 </a>
             </div>
             @if(Auth::guard('customer')->user()?->id)
             <div class="hidden md:flex justify-start space-x-5 items-center h-14">
                 <a href="{{url('/customer/orders')}}" class="flex"> My Orders
                 </a>
                 <a href="{{url('/customer/wishlist')}}" class="flex"> Wishlist
                 </a>
             </div>
             @endif
         </div>
         <div class="flex items-center space-x-5">
             <div class="hidden md:flex flex-col items-center justify-center">
                 <button class="flex relative wishlist-icon mt-1" @click="toggleWishListCart">
                     <div class="absolute flex items-center justify-center rounded-full bg-orange right-0 -top-2" style="padding:1px 4px;">
                         <span class="wish_list_counter flex text-xs text-white">0</span>
                     </div>
                     <div class="flex transition duration-300 ease-in-out text-gray-600 hover:text-orange">
                         <svg class="flex w-7 h-7" version="1.0" xmlns="http://www.w3.org/2000/svg" width="300.000000pt" height="281.000000pt" viewBox="0 0 300.000000 281.000000" preserveAspectRatio="xMidYMid meet">

                             <g transform="translate(0.000000,281.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
                                 <path d="M1970 2613 c-168 -15 -310 -75 -442 -185 l-47 -39 -68 55 c-140 112
-291 166 -468 166 -231 0 -435 -102 -573 -286 -199 -265 -234 -602 -96 -927
82 -194 171 -308 416 -528 591 -532 742 -665 764 -673 15 -6 33 -6 48 0 14 5
232 195 485 422 312 280 480 438 523 492 112 139 184 283 229 456 29 113 32
311 6 416 -45 180 -115 308 -237 429 -61 61 -96 86 -171 123 -127 62 -254 90
-369 79z m-888 -162 c120 -31 192 -73 287 -167 110 -110 112 -110 223 1 92 92
175 139 293 170 375 96 737 -230 737 -665 0 -255 -110 -489 -329 -701 -151
-145 -801 -724 -813 -724 -16 0 -790 696 -882 792 -155 164 -258 408 -261 618
-1 119 24 228 80 345 38 79 59 108 127 175 91 91 152 127 258 155 94 24 186
25 280 1z" />
                             </g>
                         </svg>
                     </div>
                 </button>
             </div>
             <div class="hidden md:flex flex-col items-center justify-center">
                 <button class="flex relative cart-icon mt-1" @click="toggleCart">
                     <div class="absolute flex items-center justify-center rounded-full bg-orange right-0 -top-2" style="padding:1px 4px;">
                         <span class="cart_counter flex text-xs text-white">0</span>
                     </div>
                     <div class="flex transition duration-300 ease-in-out text-gray-600 hover:text-orange">
                         <svg class="flex w-7 h-7" version="1.0" xmlns="http://www.w3.org/2000/svg" width="202.000000pt" height="220.000000pt" viewBox="0 0 202.000000 220.000000" preserveAspectRatio="xMidYMid meet">
                             <g transform="translate(0.000000,220.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
                                 <path d="M940 2017 c-87 -24 -159 -84 -201 -167 -19 -35 -24 -65 -27 -142 l-4
-98 -77 0 c-91 -1 -145 -12 -180 -38 -59 -44 -61 -54 -118 -556 -30 -259 -56
-516 -60 -571 -7 -116 3 -146 64 -192 l36 -28 601 -3 c331 -2 615 0 633 3 44
8 92 51 115 102 l19 43 -36 323 c-20 177 -47 421 -60 542 -14 121 -30 236 -35
257 -12 44 -66 93 -117 107 -21 6 -66 11 -99 11 l-61 0 -5 89 c-9 144 -59 232
-166 289 -71 38 -154 48 -222 29z m177 -133 c25 -15 53 -42 68 -68 21 -36 25
-55 25 -124 l0 -82 -195 0 -195 0 0 73 c1 116 49 193 141 222 54 17 101 10
156 -21z m-407 -437 c0 -37 -5 -47 -31 -66 -56 -41 -73 -114 -42 -178 44 -93
189 -99 243 -10 36 59 21 141 -35 185 -20 16 -25 29 -25 66 l0 46 195 0 195 0
0 -46 c0 -37 -5 -50 -25 -66 -78 -61 -69 -189 15 -233 40 -21 109 -19 146 4
34 21 64 75 64 115 0 39 -24 90 -54 113 -21 17 -26 29 -26 67 l0 46 75 0 c66
0 76 -2 89 -22 13 -21 127 -989 126 -1075 0 -65 19 -63 -621 -63 -549 0 -578
1 -600 19 l-23 19 58 538 c38 343 64 547 73 562 12 20 21 22 108 22 l95 0 0
-43z m75 -182 c0 -22 -31 -33 -47 -17 -17 17 -1 44 24 40 15 -2 23 -10 23 -23z
m510 0 c0 -22 -31 -33 -47 -17 -17 17 -1 44 24 40 15 -2 23 -10 23 -23z" />
                             </g>
                         </svg>
                     </div>
                 </button>
             </div>

             <div class="inline-block">
                 @if(Auth::guard('customer')->user()?->id)
                 <div class="hidden md:flex items-center dropdown-opener cursor-pointer hover:bg-gray hover:bg-opacity-10 py-1 px-2 rounded-sm transition duration-300 ease-in-out bg-transparent">
                     <img src="{{Auth::guard('customer')->user()->avatar}}" alt="user_avatar" class="flex dropdown-opener h-8 w-8 rounded-full mr-2 object-cover cursor-pointer">
                     <span class="dropdown-opener flex items-center transition duration-300 ease-in-expo text-navy text-sm font-md cursor-pointer"> {{Auth::guard('customer')->user()->name}}</span>
                 </div>
                 <a onclick="document.getElementById('logout').submit();" id="logout-button" class="text-gray-600 cursor-pointer flex md:hidden justify-between items-center hover:bg-purple hover:bg-opacity-5 transition duration-300 ease-out-expo py-2 px-5">
                     <img src="/images/icons/logout.svg" alt="logout.svg" class="mr-2 w-6"> <span class="font-bold text-sm">Logout</span>
                 </a>
                 @else
                 <a href="/customer/login" class="inline-block px-2 py-1 rounded text-xs font-md text-white bg-navy bg-opacity-10 hover:bg-opacity-30 transition duration-300 ease-in-out">Login</a><span class="text-white">/</span>
                 <a href="/customer/registration" class="inline-block px-2 py-1 rounded text-xs font-md text-white bg-navy bg-opacity-10 hover:bg-opacity-30 transition duration-300 ease-in-out">Sign Up</a>
                 @endif
             </div>
         </div>
     </div>
 </div>