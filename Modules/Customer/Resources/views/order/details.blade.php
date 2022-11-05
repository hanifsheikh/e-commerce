@extends('customer::layouts.master')
@section('content')
<div class="container mx-auto pt-20 px-0 lg:px-4 bg-gray-100 min-h-screen">
    <h2 class="text-2xl">Order Details</h2>
    <p class="text-xs font-md text-gray"> <a href="/customer/dashboard"> Dashboard </a><span class="text-xs mx-2"> &#8594; </span> Order Details </p>
    <div>
        <order-details :order_no="{{json_encode($order_no)}}" :cancellable="{{json_encode($cancellable)}}" />
    </div>
    <div>
        <div class="fixed bg-white p-5 rounded shadow-lg hidden" id="status-popup">
            <ul class="progressbar">
                <li id="status-id-1" class="">
                    <span><img src="/images/icons/bullet-icon.png"></span>
                    <span>Processing</span>
                </li>
                <li id="status-id-2" class="">
                    <span><img src="/images/icons/bullet-icon.png"></span>
                    <span>order verified</span>
                </li>
                <li id="status-id-3" class="">
                    <span><img src="/images/icons/bullet-icon.png"></span>
                    <span>ready to ship</span>
                </li>
                <li id="status-id-4" class="">
                    <span><img src="/images/icons/bullet-icon.png"></span>
                    <span>delivered</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection