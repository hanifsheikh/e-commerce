<div class="flex w-full bg-dark mt-5">
    <div class="container mx-auto xl:px-10 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-3 gap-20">
            <div class="flex flex-col space-y-8 px-5">
                <img src="/images/logo-white.png" class="flex w-48">
                <form class="flex items-start" action="/subscribe" method="POST">
                    @csrf
                    <div class="flex w-full">
                        <input name="subscriber_email" type="email" class="flex w-full text-sm px-3 h-9 py-1 text-dark rounded rounded-r-none border border-dark border-opacity-25 outline-none" placeholder="Your email address">
                    </div>
                    <button type="submit" class="bg-orange-700 font-medium text-sm text-white items-center flex h-9 rounded rounded-l-none px-6">
                        Subscribe
                    </button>
                </form>

                <div class="flex items-center justify-between">
                    <a href="">
                        <img src="/images/playstoredownload.svg" class="flex w-40">
                    </a>
                    <a href="">
                        <img src="/images/appstoredownlad.svg" class="flex w-40">
                    </a>
                </div>
            </div>
            <div class="flex flex-col space-y-8 px-5 lg:px-0">
                <div class="flex flex-col space-y-2">
                    <p class="flex font-bold text-white text-sm uppercase">CONTACT INFO</p>
                    <div class="flex bg-dark w-full" style="height: 1px;"></div>
                </div>
                <div class="flex flex-col space-y-1">
                    <p class="flex font-bold text-gray text-sm">Address:</p>
                    <p class="flex font-normal text-white text-sm">House 11 (1st Floor), Road 13, Sector 11, Shonir Akhra,
                        Dhaka-1204</p>
                </div>
                <div class="flex flex-col space-y-1">
                    <p class="flex font-bold text-gray text-sm">Helpline:</p>
                    <p class="flex font-normal text-white text-sm">+880 1515 200545</p>
                </div>
                <div class="flex flex-col space-y-1">
                    <p class="flex font-bold text-gray text-sm">Email:</p>
                    <p class="flex font-normal text-white text-sm">contact@jenexmart.com</p>
                </div>
            </div>
            <div class="flex flex-col space-y-8 px-5 lg:px-0">
                <div class="flex flex-col space-y-2">
                    <p class="flex font-bold text-white text-sm uppercase">MY account</p>
                    <div class="flex bg-dark w-full" style="height: 1px;"></div>
                </div>
                <ul class="flex flex-col space-y-3">
                    <li class="flex font-medium text-gray text-sm"><a href="/customer/login"> Login</a></li>
                    <li class="flex font-medium text-gray text-sm"><a href="/customer/orders"> Order History</a></li>
                    <li class="flex font-medium text-gray text-sm"><a href="/customer/wishlist"> My Wishlist</a></li>
                    <!-- <li class="flex font-medium text-gray text-sm"><a href=""> Track Order</a></li> -->
                </ul>
                <div class="flex flex-col space-y-2">
                    <p class="flex font-bold text-white text-sm uppercase">Be a seller</p>
                    <div class="flex bg-dark w-full" style="height: 1px;"></div>
                    <div class="flex">
                        <a href="/seller/registration" class="inline-flex items-center bg-orange-700 font-medium text-sm text-white items-center h-9 rounded px-6">
                            Apply Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="flex w-full bg-deepest py-3 mb-14 md:mb-0">
    <div class="container mx-auto xl:px-10 ">
        <div class="flex w-full justify-between lg:justify-start px-5 lg:px-0">
            <div class="flex w-2/3 lg:w-1/3 space-x-1 items-center">
                <img src="/images/icons/copyright.svg" class="w-3 flex">
                <p class=" flex text-white text-xs font-medium">JenexMart Limited {{date('Y')}} | All Rights Reserved</p>
            </div>
            <div class="flex w-auto lg:w-1/3 items-center justify-end lg:justify-center space-x-3">
                <a href="https://web.facebook.com/JenexMart" target="_blank">
                    <img src="/images/icons/facebook.svg" class="flex w-6 object-contain">
                </a>
                <a href="https://www.instagram.com/jenexmart/" target="_blank">
                    <img src="/images/icons/instagram.svg" class="flex w-6 object-contain">
                </a>
                <a href="https://web.facebook.com/jenexMart" target="_blank">
                    <img src="/images/icons/linkedin.svg" class="flex w-6 object-contain">
                </a>
            </div>
            <div class="flex hidden lg:visible w-1/3"></div>
        </div>
    </div>
</div>