<div>
    <filtersidebar />
</div>
<div id="sidebar-container" class="sidebar-container fixed lg:hidden h-screen right-full overflow-y-scroll bg-white shadow-xl w-4/5 sm:w-2/3 md:w-1/2 z-50">
    <div class="flex w-full border-b border-light">
        <div class="flex w-full px-5 pt-5 pb-3 flex-row justify-between items-center">
            <div class="flex flex-col justify-start">
                <a href="/" class="mb-1">
                    <img src="{{asset('/images/logo.png')}}" alt="{{asset('/images/logo.png')}}" class="h-5 md:h-8 lg:h-10">
                </a>
                <a href="/" class="text-xs text-gray-600">{{config('app.slogan')}}</a>
            </div>
            <div class="flex items-center" id="sidebar-close">
                <img src="{{asset('/images/icons/close.png')}}" alt="{{asset('/images/icons/close.png')}}" class="h-6">
            </div>
        </div>
    </div>
    <div class="flex w-full flex-col p-5">
        <span class="flex text-orange mb-2 text-base font-bold">Categories</span>
        <ul class="flex flex-col list-none">
            @foreach($categories as $parent)
            <li class="flex flex-col space-y-2 w-full py-3 border-b border-light" @click="showSecondLevel($event, {{$parent->id}})">
                <div class="flex flex-row justify-between items-center">
                    <a @click="handleClickPropagation($event)" href="/category/{{$parent->category_url}}" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-bold">{{$parent->category_name}}</a>
                    @if(count($parent->childrens))
                    <svg id="{{'category-chevron-'. $parent->id}}" style="height: 6px;" class="category-chevron flex transform rotate-0 -rotate-90 transition duration-300 text-navy ease-in-out" width="13" height="8" viewBox="0 0 13 8" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.5 8L0.00480908 0.5L12.9952 0.500001L6.5 8Z" fill="currentColor" />
                    </svg>
                    @endif
                </div>
                @if(count($parent->childrens))
                <ul id="{{'category-'. $parent->id}}" class="inactive hidden second-level flex ml-2 pl-2 border-l border-gray-200 border-dashed flex-col items-start list-none">
                    @foreach($parent->childrens as $second) <li class="flex flex-col space-y-2 w-full h-full py-3 border-b border-light" @click="showThirdLevel($event, {{$second->id}})">
                        <div class="flex flex-row justify-between items-center">
                            <a @click="handleClickPropagation($event)" href="/category/{{$second->category_url}}" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-normal">{{$second->category_name}}</a>
                            @if(count($second->childrens))
                            <svg id="{{'category-chevron-'. $second->id}}" style="height: 6px;" class="category-chevron flex transform rotate-0 -rotate-90 transition duration-300 text-navy ease-in-out" width="13" height="8" viewBox="0 0 13 8" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.5 8L0.00480908 0.5L12.9952 0.500001L6.5 8Z" fill="currentColor" />
                            </svg>
                            @endif
                        </div>
                        @if(count($second->childrens))
                        <ul id="{{'category-'. $second->id}}" class="inactive hidden third-level flex ml-2 pl-2 border-l border-gray-200 border-dashed flex-col items-start list-none">
                            @foreach($second->childrens as $third) <li @click="handleClickPropagation($event)" class="flex w-full py-3 border-b border-light">
                                <div class="flex flex-row justify-start items-center">
                                    <a href="/category/{{$third->category_url}}" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-light">{{$third->category_name}}</a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
    <div class="flex w-full justify-center items-center space-x-3 p-5">
        <a href="/customer/login" class="rounded py-2 px-3 text-white bg-orange text-xs font-bold">Login</a>
        <span class="text-xs text-gray font-light"> / </span>
        <a href="/customer/registration" class="rounded py-2 px-3 text-white bg-navy text-xs font-bold">Sign Up</a>
    </div>
    <div class="flex w-full flex-col p-5">
        <span class="flex text-orange mb-2 text-base font-bold">Site Links</span>
        <ul class="flex flex-col list-none">
            <li class="flex flex-col space-y-2 w-full py-3 border-b border-light" @click="showOffers($event)">
                <div class="flex flex-row justify-between items-center" @click="showOffers($event)">
                    <a @click="showOffers($event)" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-bold">Offers</a>
                    <svg id="offers-chevron" style="height: 6px;" class="offers-chevron flex transform rotate-0 -rotate-90 transition duration-300 text-navy ease-in-out" width="13" height="8" viewBox="0 0 13 8" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.5 8L0.00480908 0.5L12.9952 0.500001L6.5 8Z" fill="currentColor" />
                    </svg>
                </div>
                <ul id="offers" class="inactive hidden flex ml-2 pl-2 border-l border-gray-200 border-dashed flex-col items-start list-none">
                    @foreach($offers as $offer)
                    <li class="flex flex-col space-y-2 w-full h-full py-3 border-b border-light">
                        <div class="flex flex-row justify-between items-center">
                            <a @click="handleClickPropagation($event)" href="/offer/{{$offer->slug}}" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-normal">{{ $offer->offer_title }}</a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li class="flex flex-col space-y-2 w-full py-3 border-b border-light">
                <a href="/new-arrivals" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-bold">New In</a>
            </li>
            <li class="flex flex-col space-y-2 w-full py-3 border-b border-light">
                <a href="/daily-deals" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-bold">Daily Deals</a>
            </li>
            <li class="flex flex-col space-y-2 w-full py-3 border-b border-light">
                <a href="/sellers" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-bold">Sellers</a>
            </li>
            <li class="flex flex-col space-y-2 w-full py-3 border-b border-light">
                <a href="/brands" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-bold">Brands</a>
            </li>
            <li class="flex flex-col space-y-2 w-full py-3 border-b border-light">
                <a href="/collections" class="flex items-center text-navy hover:text-orange transition duration-300 ease-in-out text-sm font-bold">Collections</a>
            </li>
        </ul>
    </div>

    <div class="py-5"></div>
</div>

<div class="fixed bg-light-gray-100 w-full text-dark z-20">
    <div class="bg-white border-b border-light-gray flex w-full">
        <div class="container mx-auto px-3 xl:px-10">
            <div class="flex items-center justify-between pt-3 pb-2">
                <div class="flex items-center">
                    <p class="flex items-center text-xxs text-gray-600 uppercase"> Welcome to {{env('APP_NAME')}}!</p>
                </div>
                <div class="flex justify-end items-center space-x-5">
                    @if(Auth::guard('customer')->user()?->id)
                    <p class="flex items-center text-xxs text-gray-600 hover:text-dark transition duration-300 ease-in-out">Hi, {{Auth::guard('customer')->user()->name}}!</p>
                    @else
                    <a href="/seller/registration" class="flex items-center text-xxs text-gray-600 uppercase hover:text-dark transition duration-300 ease-in-out">Be A Seller</a>
                    <a href="/customer/login" class="flex items-center text-xxs text-gray-600 uppercase hover:text-dark transition duration-300 ease-in-out">SignUp / Login</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="header">
        <!-- ## Logo, Searchbar, Wishlist, Cart, Login/Sign Up Button Starts Here ## -->
        <div class="flex w-full backdrop-filter backdrop-blur-lg lg:backdrop-blur-none bg-white bg-opacity-80 lg:bg-opacity-100">
            <div class="container mx-auto px-3 xl:px-10">
                <div class="flex items-center space-x-5 lg:space-x-10 justify-between py-3">
                    <div class="flex flex-row items-center">
                        <button id="sidebar-toggler" class="select-none flex lg:hidden items-center justify-around mr-4">
                            <div class="select-none flex flex-col w-5 space-y-1">
                                <span class="select-non flex hamburger-menu-line w-full bg-gray-600 transition duration-300 ease-in-out rounded" style="height: 2px;"></span>
                                <span class="select-non flex hamburger-menu-line w-full bg-gray-600 transition duration-300 ease-in-out rounded" style="height: 2px;"></span>
                                <span class="select-non flex hamburger-menu-line w-full bg-gray-600 transition duration-300 ease-in-out rounded" style="height: 2px;"></span>
                            </div>
                        </button>
                        <div class="flex justify-start flex-col space-y-1">
                            <a href="/">
                                <img src="{{asset('/images/logo.png')}}" class="h-4 w-full md:h-8 lg:h-10">
                            </a>
                            <a href="/" class="ml-1 hidden md:flex text-xs text-gray-600">{{env('APP_SLOGAN')}}</a>
                        </div>
                    </div>
                    <div class="hidden xl:flex xl:w-7 2xl:w-14">
                    </div>
                    <div class="flex items-center flex-shrink lg:flex-grow lg:px-10">
                        <form action="/product_search" method="GET" class="hidden md:flex items-center flex-grow border-2 border-gray hover:border-orange hover:border-opacity-100 transition duration-300 ease-in-out border-opacity-75" style=" border-radius:2rem">
                            <input type="text" value="{{ isset($query) ? $query['query'] : null }}" name="query" spellcheck="false" class="flex flex-grow my-1 text-xs md:text-sm h-8 md:h-9 mx-4 text-dark outline-none" placeholder="What are you looking for..." style="background: transparent;padding-top: 5px; padding-bottom:5px;">
                            <button type="submit" class="items-center flex rounded-full p-2 pr-4 text-gray hover:text-orange">
                                <svg class="flex w-5 h-5" fill="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
                                    <g>
                                        <path d="M932.8,850l-201-201c56.4-67.6,90.3-154.5,90.3-249.5C822.2,184.2,647.9,10,432.7,10C217.4,10,43.2,184.2,43.2,399.5C43.2,614.7,217.4,789,432.7,789c61.1,0,119-14.1,170.5-39.1c3,4.7,6.6,9.1,10.7,13.2l203,203c32,32,84,32,116,0C964.8,934,964.8,882,932.8,850z M125.2,399.5C125.2,229.7,262.9,92,432.7,92s307.5,137.7,307.5,307.5c0,169.8-137.8,307.5-307.5,307.5C262.9,707,125.2,569.3,125.2,399.5z" />
                                    </g>
                                </svg>
                            </button>
                        </form>
                        <form action="/product_search" method="GET" class="flex md:hidden items-center w-50 flex-grow border border-gray-200 hover:border-orange hover:border-opacity-100 transition duration-300 ease-in-out border-opacity-75" style="border-radius:0.5rem">
                            <input type="text" value="{{ isset($query) ? $query['query'] : null }}" name="query" spellcheck="false" class="flex flex-grow mx-2 text-xs md:text-sm h-8 md:h-9 w-22 text-dark outline-none" placeholder="Search Products..." style="background:transparent;margin-top:0px; margin-bottom:0px;">
                            <button type="submit" class="items-center flex rounded-full p-2 pr-2 text-gray hover:text-orange">
                                <svg class="flex w-5 h-5" fill="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
                                    <g>
                                        <path d="M932.8,850l-201-201c56.4-67.6,90.3-154.5,90.3-249.5C822.2,184.2,647.9,10,432.7,10C217.4,10,43.2,184.2,43.2,399.5C43.2,614.7,217.4,789,432.7,789c61.1,0,119-14.1,170.5-39.1c3,4.7,6.6,9.1,10.7,13.2l203,203c32,32,84,32,116,0C964.8,934,964.8,882,932.8,850z M125.2,399.5C125.2,229.7,262.9,92,432.7,92s307.5,137.7,307.5,307.5c0,169.8-137.8,307.5-307.5,307.5C262.9,707,125.2,569.3,125.2,399.5z" />
                                    </g>
                                </svg>
                            </button>
                        </form>
                    </div>
                    <div class="hidden xl:flex xl:w-7 2xl:w-14">

                    </div>
                    <div class="hidden lg:flex">
                        <a href="tel:+8801515200545" class="flex items-center space-x-1 cursor-pointer  transition duration-300 ease-in-out text-gray-600 hover:text-orange">
                            <svg class="flex w-7 h-7" style="transform: rotate(-85deg);" fill="currentColor" version="1.0" xmlns="http://www.w3.org/2000/svg" width="64.000000pt" height="64.000000pt" viewBox="0 0 64.000000 64.000000" preserveAspectRatio="xMidYMid meet">

                                <g transform="translate(0.000000,64.000000) scale(0.100000,-0.100000)" stroke="none">
                                    <path d="M376 550 c-128 -65 -224 -160 -286 -284 -41 -81 -39 -107 11 -161 55
                                    -59 74 -58 138 8 57 58 62 83 26 117 -24 22 -24 24 -7 53 20 34 99 107 117
                                    107 6 0 22 -11 35 -25 13 -14 31 -25 40 -25 25 0 130 102 130 127 0 27 -92
                                    113 -122 113 -13 0 -50 -14 -82 -30z m127 -48 l37 -38 -45 -44 -45 -44 -33 32
                                    -33 32 -54 -33 c-40 -23 -67 -50 -98 -97 l-44 -64 35 -32 35 -32 -44 -43 -44
                                    -43 -35 34 c-19 19 -35 41 -35 50 0 9 14 46 32 81 42 86 160 203 248 246 36
                                    18 70 32 75 33 6 0 28 -17 48 -38z" />
                                </g>
                            </svg>

                            <div class="flex flex-col ">
                                <p class="flex text-xs">Call Us Now:</p>
                                <p class="flex text-xs xl:text-sm font-bold">01515-200545</p>
                            </div>
                        </a>
                    </div>
                    <div class="hidden md:flex">
                        @if(Auth::guard('customer')->user()?->id)
                        <p class="relative dropdown-opener flex items-center cursor-pointer transition duration-300 ease-in-out text-gray-600 hover:text-orange z-50">
                            <a href="/customer/dashboard"> <img src="{{Auth::guard('customer')->user()->avatar}}" class="border-2 border-light flex w-10 h-10 rounded-full object-cover" alt="{{Auth::guard('customer')->user()->avatar}}"> </a>
                        </p>
                        @else
                        <a href="/customer/login" class="flex items-center cursor-pointer transition duration-300 ease-in-out text-gray-600 hover:text-orange">
                            <svg class="flex w-8 h-8" version="1.0" xmlns="http://www.w3.org/2000/svg" width="300.000000pt" height="299.000000pt" viewBox="0 0 300.000000 299.000000" preserveAspectRatio="xMidYMid meet">
                                <g transform="translate(0.000000,299.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
                                    <path d="M1244 2875 c-578 -101 -1029 -550 -1139 -1135 -24 -124 -24 -350 -2
-477 126 -697 737 -1191 1437 -1160 645 28 1174 480 1312 1120 31 145 31 409
0 549 -123 559 -529 968 -1085 1093 -101 23 -414 29 -523 10z m449 -150 c379
-66 714 -308 890 -644 101 -192 141 -357 141 -581 1 -215 -35 -373 -124 -553
-40 -79 -120 -207 -130 -207 -3 0 -25 34 -49 74 -103 173 -257 316 -440 409
-58 29 -117 57 -131 61 -25 6 -25 6 15 38 113 89 185 191 231 323 25 71 28 94
28 205 0 110 -3 134 -26 198 -74 205 -215 345 -417 412 -203 68 -431 28 -601
-106 -163 -128 -262 -351 -245 -553 16 -187 119 -374 265 -480 l40 -28 -48
-18 c-243 -91 -441 -267 -588 -520 -10 -18 -14 -15 -47 30 -87 121 -165 305
-203 480 -23 109 -23 340 0 457 88 453 402 813 835 958 48 16 124 36 167 44
106 19 330 20 437 1z m-62 -405 c351 -109 464 -549 209 -814 -168 -175 -426
-207 -626 -78 -113 73 -181 168 -215 298 -65 247 74 503 320 590 87 31 221 33
312 4z m-41 -1130 c319 -39 595 -232 738 -516 l31 -61 -42 -41 c-140 -135
-368 -250 -589 -298 -105 -23 -369 -26 -473 -5 -159 31 -340 106 -470 194 -89
59 -175 137 -175 156 0 36 107 197 188 282 208 219 497 324 792 289z" />
                                </g>
                            </svg>
                        </a>
                        @endif
                    </div>
                    <div class="hidden md:flex">
                        <div class="flex items-center space-x-5">
                            <div class="flex flex-col items-center justify-center space-y-1">
                                <button class="flex relative wishlist-icon" @click="toggleWishListCart">
                                    <div class="absolute flex items-center justify-center rounded-full  right-0 -top-3 bg-orange" style="padding:1px 4px;">
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
                            <div class="flex flex-col items-center justify-center space-y-1">
                                <button class="flex relative cart-icon" @click="toggleCart">
                                    <div class="absolute flex items-center justify-center rounded-full bg-orange right-0 -top-3" style="padding:1px 4px;">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Logo, Searchbar, Wishlist, Cart, Login/Sign Up Button Ends Here -->
        <!-- Navbar Starts Here  -->
        <div id="navbar" class="hidden lg:flex w-full bg-dark">
            <div class="relative container mx-auto xl:px-10">
                <div class="flex space-x-14">
                    <button id="categories-trigger-button" class="flex bg-paste items-center justify-around px-5 space-x-5" style="padding-top: 10px ; padding-bottom:10px;">
                        <div class="flex flex-col w-5 h-3 justify-between">
                            <span class="cateogry-hamburger-line flex w-full bg-white bg-dark transition duration-300 ease-in-out rounded" style="height: 2px;"></span>
                            <span class="cateogry-hamburger-line flex w-full bg-white bg-dark transition duration-300 ease-in-out rounded" style="height: 2px;"></span>
                            <span class="cateogry-hamburger-line flex w-full bg-white bg-dark transition duration-300 ease-in-out rounded" style="height: 2px;"></span>
                        </div>
                        <span class="category-menu-label flex text-xs xl:text-base text-white text-dark transition duration-300 ease-in-out uppercase font-medium">Categories</span>
                        <svg style="height: 6px;" class="category-chevron flex transform rotate-0 -rotate-90 transition duration-300 text-white text-dark ease-in-out" width="13" height="8" viewBox="0 0 13 8" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.5 8L0.00480908 0.5L12.9952 0.500001L6.5 8Z" fill="currentColor" />
                        </svg>
                    </button>
                    <ul class="h-11 flex space-x-8 items-center">
                        <li class="relative flex h-full items-center">
                            <a href="/new-arrivals" class="text-white font-medium text-xs xl:text-base">New In</a>

                            @if(request()->is('new-arrivals'))
                            <span class="absolute bottom-0 bg-orange w-full h-1"></span>
                            @endif
                        </li>
                        <li class="relative flex h-full items-center">
                            <a href="/daily-deals" class="text-white font-medium text-xs xl:text-base">Daily Deals</a>
                            @if(request()->is('daily-deals'))
                            <span class="absolute bottom-0 bg-orange w-full h-1"></span>
                            @endif
                        </li>
                        <li class="relative flex h-full items-center"> <a href="/sellers" class="text-white font-medium text-xs xl:text-base">Sellers</a>
                            @if(request()->is('sellers') || request()->is('store/*') )
                            <span class="absolute bottom-0 bg-orange w-full h-1"></span>
                            @endif
                        </li>
                        <li class="relative flex h-full items-center"> <a href="/brands" class="text-white font-medium text-xs xl:text-base">Brands</a>
                            @if(request()->is('brands') || request()->is('brand/*') )
                            <span class="absolute bottom-0 bg-orange w-full h-1"></span>
                            @endif
                        </li>
                        <!-- <li class="relative flex h-full items-center">
                           
                            <a class="text-gray-600 font-medium text-xs xl:text-base">Must Have</a>
                        </li> -->
                        <li class="relative flex h-full items-center"> <a href="/collections" class="text-white font-medium text-xs xl:text-base">Collections</a>
                            @if(request()->is('collections') || request()->is('collection/*') )
                            <span class="absolute bottom-0 bg-orange w-full h-1"></span>
                            @endif
                        </li>
                        <li class="relative flex h-full items-center"> <button id="offers-trigger-button" class="text-white font-medium text-xs xl:text-base">Offers</button>
                            @if(request()->is('offer/*') )
                            <span class="absolute bottom-0 bg-orange w-full h-1"></span>
                            @endif
                            <div class="w-72 absolute top-11 -left-5 shadow-xl hide mx-auto z-20 bg-white rounded p-3" id="offer-dropdown-menu">
                                <ul id="offers-list-container" class="flex justify-center flex-col overflow-y-auto" style=" max-height: 90%">
                                    <?php $i = 0; ?>
                                    @foreach($offers as $offer)
                                    <?php $i++; ?>
                                    @if(count($offers) == $i )
                                    <li class="relative flex items-center">
                                        <a href="/offer/{{$offer->slug}}" class="text-navy hover:text-orange transition duration-300 ease-in-out font-medium text-xs xl:text-base">
                                            {{$offer->offer_title}}
                                        </a>
                                    </li>
                                    @else
                                    <li class="relative flex items-center">
                                        <a href="/offer/{{$offer->slug}}" class="text-navy hover:text-orange transition duration-300 ease-in-out font-medium text-xs xl:text-base">
                                            {{$offer->offer_title}}
                                        </a>
                                    </li> <span class="flex my-1 w-full border-t border-light"></span>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div> <!-- Navbar Ends  -->
        @include('includes.category_dropdown_menu')
    </div>
</div>