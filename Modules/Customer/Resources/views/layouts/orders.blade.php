<?php

use Carbon\Carbon; ?>
@extends('customer::dashboard')

@section('dashboard-view')
<div class="container px-5 lg:mx-auto">
    <h2 class="text-2xl">My Orders</h2>
    <div class="bg-white shadow-md overflow-x-auto rounded-md mb-6 mt-5">
        <div class="table w-full">
            <table class="w-full table-auto">
                <thead>
                    <tr class="px-20 bg-white text-gray-600 uppercase text-xs leading-normal border-b-2 border-light">
                        <th class="px-3 pl-5 py-2 text-left font-md border-b border-light text-center"> Order Number</th>
                        <th class="px-3 pl-5 py-2 text-left font-md border-b border-light text-center"> Order Amount</th>
                        <th class="hidden lg:inline-block px-3 pl-5 py-2 text-left font-md border-b border-light text-center"> Shipping Address</th>
                        <th class="hidden lg:inline-block px-3 pl-5 py-2 text-left font-md border-b border-light text-center"> Receiver Name</th>
                        <th class="hidden lg:inline-block px-3 pl-5 py-2 text-left font-md border-b border-light text-center"> Receiver Contact No.</th>
                        <th class="px-3 pl-5 py-2 text-left font-md border-b border-light text-center"> Created</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-xs font-light">
                    @foreach($orders as $order)
                    <tr @click.prevent="showOrder({{json_encode($order->order_no)}})" class="bg-white hover:bg-light transition duration-500 ease-in-expo">
                        <td class="px-3 pl-5 py-2 text-left font-bold border-b border-light text-center cursor-pointer">
                            {{$order->order_no}}
                        </td>
                        <td class="px-3 pl-5 py-2 text-left font-md border-b border-light text-center cursor-pointer">
                            <span class="text-xs font-bold text-green">৳ </span> {{$order->cart_total}}
                        </td>
                        <td class="hidden lg:inline-block px-3 pl-5 py-2 text-left font-md border-b border-light text-center cursor-pointer">
                            {{$order->address}}, {{$order->district}}
                        </td>
                        <td class="hidden lg:inline-block px-3 pl-5 py-2 text-left font-md border-b border-light text-center cursor-pointer">
                            {{$order->receiver_name}}
                        </td>
                        <td class="hidden lg:inline-block px-3 pl-5 py-2 text-left font-md border-b border-light text-center cursor-pointer">
                            {{$order->receiver_contact_no}}
                        </td>
                        <td class="px-3 pl-5 py-2 text-left font-md border-b border-light text-center cursor-pointer">
                            {{Carbon::parse($order->created_at)->toDayDateTimeString()}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection