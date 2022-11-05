<div class="fixed md:hidden bottom-0 w-full shadow-upside text-dark z-40">
    <div class="backdrop-filter backdrop-blur-lg bg-white bg-opacity-80 border-b border-light-gray flex w-full">
        <div class="container mx-auto px-3 xl:px-10">
            <div class="flex relative items-center justify-around py-1 mt-1">
                <div class="flex flex-col items-center justify-center">
                    <button class="flex relative cart-icon" @click="toggleCart">
                        <div class="flex transition duration-300 ease-in-out text-gray-600 hover:text-orange">
                            <svg class="flex w-5 h-5" version="1.0" xmlns="http://www.w3.org/2000/svg" width="202.000000pt" height="220.000000pt" viewBox="0 0 202.000000 220.000000" preserveAspectRatio="xMidYMid meet">
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
                    <span class="font-medium text-xs text-navy">Cart</span>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <button class="flex relative wishlist-icon" @click="toggleWishListCart">
                        <div class="flex transition duration-300 ease-in-out text-gray-600 hover:text-orange">
                            <svg class="flex w-5 h-5" version="1.0" xmlns="http://www.w3.org/2000/svg" width="300.000000pt" height="281.000000pt" viewBox="0 0 300.000000 281.000000" preserveAspectRatio="xMidYMid meet">
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
                    <span class="font-medium text-xs text-navy">Wish List</span>
                </div>
                <div class="flex flex-row items-center justify-center w-12">
                    <div class="absolute -top-6">
                        <a href="/" class="select-none flex relative home-icon bg-white p-3 rounded-full border-opacity-60 hover:border-orange hover:border-opacity-80 transition duration-300 ease-in-out border-4 border-gray-200 shadow-lg">
                            <img src="/images/icons/home.png" src="home" class="select-none w-8 h-8 opacity-60">
                        </a>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <a href="/daily-deals" class="flex relative">
                        <div class="flex transition duration-300 ease-in-out {{request()->is('daily-deals') ? 'text-orange' :'text-gray-600'}} hover:text-orange">
                            <svg class="flex w-5 h-5" viewBox="0 0 1000 1000" fill="currentColor">
                                <g>
                                    <path d="M962.8,527.2h-54.4c-15.2,0-27.2-12-27.2-27.2s12-27.2,27.2-27.2h54.4c15.2,0,27.2,12,27.2,27.2S978,527.2,962.8,527.2z M808.2,847.4L778.8,818c-10.9-10.9-10.9-28.3,0-38.1c10.9-10.9,28.3-10.9,38.1,0l29.4,29.4c10.9,10.9,10.9,28.3,0,38.1C836.5,858.2,819,858.2,808.2,847.4z M818,221.2c-10.9,10.9-28.3,10.9-38.1,0c-10.9-10.9-10.9-28.3,0-38.1l29.4-29.4c10.9-10.9,28.3-10.9,38.1,0c10.9,10.9,10.9,28.3,0,38.1L818,221.2z M500,826.7c-180.8,0-326.7-145.9-326.7-326.7c0-180.8,145.9-326.7,326.7-326.7S826.7,319.2,826.7,500C826.7,680.8,680.8,826.7,500,826.7z M500,230c-149.2,0-270,120.9-270,270s120.9,270,270,270s270-120.9,270-270S649.2,230,500,230z M500,118.9c-15.2,0-27.2-12-27.2-27.2V37.2c0-15.2,12-27.2,27.2-27.2s27.2,12,27.2,27.2v54.4C527.2,106.9,515.2,118.9,500,118.9z M182,221.2l-29.4-29.4c-10.9-10.9-10.9-28.3,0-38.1c10.9-9.8,28.3-10.9,38.1,0l29.4,29.4c10.9,10.9,10.9,28.3,0,38.1C209.3,231,192.9,232.1,182,221.2z M91.7,527.2H37.2C22,527.2,10,515.2,10,500s12-27.2,27.2-27.2h54.4c15.2,0,27.2,12,27.2,27.2S106.9,527.2,91.7,527.2z M183.1,777.7c10.9-10.9,28.3-10.9,38.1,0c10.9,10.9,10.9,28.3,0,38.1l-29.4,29.4c-10.9,10.9-28.3,10.9-38.1,0c-10.9-10.9-10.9-28.3,0-38.1L183.1,777.7z M500,881.1c15.2,0,27.2,12,27.2,27.2v54.4c0,15.2-12,27.2-27.2,27.2s-27.2-12-27.2-27.2v-54.4C472.8,893.1,484.8,881.1,500,881.1z" />
                                </g>
                            </svg>
                        </div>
                    </a>
                    <span class="font-medium text-xs text-navy">Daily Deals</span>
                </div>
                <div class="flex flex-col items-center justify-center">
                    @if(Auth::guard('customer')->user()?->id)
                    <p class="dropdown-opener flex items-center cursor-pointer transition duration-300 ease-in-out text-gray-600 hover:text-orange">
                        <a href="/customer/dashboard">
                            <img src="{{Auth::guard('customer')->user()->avatar}}" class="flex w-10 h-10 rounded-full border-2 border-light object-cover" alt="{{Auth::guard('customer')->user()->avatar}}">
                        </a>
                    </p>
                    @else
                    <a href="/customer/login" class="flex items-center cursor-pointer transition duration-300 ease-in-out text-gray-600 hover:text-orange">
                        <svg class="flex w-5 h-5" version="1.0" xmlns="http://www.w3.org/2000/svg" width="300.000000pt" height="299.000000pt" viewBox="0 0 300.000000 299.000000" preserveAspectRatio="xMidYMid meet">
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
                    <span class="font-medium text-xs text-navy">Account</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>