<?php include(app_path() . '/includes/districts.php');
include(app_path() . '/includes/postcodes.php');
array_multisort(array_column($districts, 'name'), SORT_ASC, SORT_NATURAL | SORT_FLAG_CASE, $districts);
?>

@extends('layouts.master')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-center flex-wrap items-start">
        <div class="flex flex-col w-full mx-2 lg:mx-10 lg:flex-row shadow-xl rounded-md bg-white top-14 right-0 transition duration-300 ease-out-expo">
            <div class="flex w-full items-center lg:w-6/4 flex-col space-y-5 p-5">
                <h2 class="text-2xl font-bold mb-5 text-gray-800">Shipping Address</h2>
                <div class="flex flex-col w-full items-center">
                    <div class="flex w-full md:space-x-5 flex-col-reverse md:flex-row lg:w-4/5 {{count($addresses) ? 'xl:w-4/6' : 'xl:w-1/3'  }} overflow-hidden">
                        <div class="{{count($addresses) ? 'md:w-1/2' :  'md:w-full' }} flex flex-col">
                            <h2 class="text-md font-light text-gray">Create New Address</h2>
                            <form id="save-shipping-address" class="space-y-3" action="/order/shipping-address" method="POST">
                                @csrf
                                <div>
                                    <label class="block mb-1 font-bold text-sm text-gray-500">Receiver Name</label>
                                    <input value="{{old('receiver_name')}}" type="text" class="{{$errors->has('receiver_name') ? 'border-red' : 'border-gray-200'}} w-full border-2 p-2 rounded outline-none focus:border-purple-500" name="receiver_name">
                                </div>
                                <div>
                                    <label class="block mb-1 font-bold text-sm text-gray-500">Contact Number</label>
                                    <div class="flex items-center">
                                        <div class="{{$errors->has('receiver_contact_no') ? 'border-red' : 'border-gray-200'}} w-full border-2 border-gray-200 p-2 rounded outline-none focus:border-purple-500">
                                            +88
                                            <input value="{{old('receiver_contact_no')}}" type="number" class="outline-none" name="receiver_contact_no">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block mb-1 font-bold text-sm text-gray-500">
                                        Address
                                    </label>
                                    <textarea spellcheck="false" class="{{$errors->has('receiver_address') ? 'border-red' : 'border-gray-200'}} w-full border-2 border-gray-200 p-2 rounded outline-none focus:border-purple-500" name="receiver_address">{{ old('receiver_address') }}</textarea>
                                </div>
                                <div>
                                    <label class="block mb-1 font-bold text-sm text-gray-500">District</label>
                                    <select id="district" @change="updateArea()" required name="district" class="w-full p-2 rounded bg-white border-2 border-gray-200 outline-none">
                                        @foreach($districts as $district)
                                        <option data-district-id="{{$district['id']}}" {{ old('district') ==  $district['name']  ? "selected" : "" }} value="{{$district['name']}}">{{$district['name'] . ' - ' . $district['bn_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-1 font-bold text-sm text-gray-500">Area</label>
                                    <select id="area" required name="area" class="w-full p-2 rounded bg-white border-2 border-gray-200 outline-none">
                                        @foreach($areas as $area)
                                        <option value="{{$area['postOffice']}}">{{$area['upazila'] . ' - ' . $area['postOffice']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-center block mb-1 font-bold text-sm text-gray-500">
                                        Address label (<span id="selected-label-text" class="capitalize">home</span>)
                                    </label>
                                    <div class="flex w-full justify-around space-x-5">
                                        <div class="address-label transition duration-300 ease-in-out flex flex-col px-4 py-2 items-center justify-center bg-light hover:bg-light border border-gray-200 shadow rounded cursor-pointer" @click="selectLabel($event,'home')">
                                            <img src="{{asset('/images/icons/home-icon.svg')}}" class="flex h-12 object-cover">
                                            <p class="flex font-bold text-navy text-sm">Home</p>
                                        </div>
                                        <div class="address-label transition duration-300 ease-in-out flex flex-col px-4 py-2 items-center justify-center bg-white hover:bg-light border border-gray-200 shadow rounded cursor-pointer" @click="selectLabel($event,'office')">
                                            <img src="{{asset('/images/icons/building.png')}}" class="flex h-12 object-cover">
                                            <p class="flex font-bold text-navy text-sm">Office</p>
                                        </div>

                                    </div>
                                    <input type="hidden" id="label-input" name="label" value="home">
                                </div>
                                @if($errors->any())
                                @foreach($errors->all() as $error)
                                <p class="text-red text-xs inline-block text-bold bg-red py-1 px-2 bg-opacity-10 my-2"> {{ $error }}</p>
                                @endforeach
                                @endif
                                <button class="block w-full bg-blue p-2 shadow-md rounded text-white hover:text-light hover:bg-blue-700 font-bold transition duration-300">Save</button>
                            </form>
                        </div>
                        <address-list :addresses="{{json_encode($addresses)}}" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection