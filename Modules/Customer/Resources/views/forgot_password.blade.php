@extends('customer::layouts.auth-master')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-customer-login flex-col">
    <div class="bg-white border border-gray-200 p-8 rounded shadow-xl">
        <h2 class="text-2xl font-bold mb-5 text-gray-800">Password Recovery</h2>
        <p>If you forgot your password, don't worry!</p>
        <p> Contact with us via phone call or WhatsApp number given below.</p>
        <p class="font-bold text-lg">+880 - 1515 - 200545</p>
        <p class="text-sm text-muted text-gray my-2">Note : Calling contact no. or WhatsApp contact no. must be matched with your account contact no.</p>

        <div class="py-5"></div>
        <p>আপনি যদি আপনার পাসওয়ার্ডটি ভুলে গিয়ে থাকেন, চিন্তার কিছু নেই!</p>
        <p> নিচের নম্বরটিতে ফোন কল কিংবা WhatsApp এর সাহায্যে আমাদের সাথে যোগাযোগ করুন</p>
        <p class="font-bold text-lg">+880 - 1515 - 200545</p>
        <p class="text-sm text-muted text-gray my-2">উল্লেখ্য : আপনার অ্যাকাউন্ট এর কন্টাক্ট নম্বর এবং আপনার WhatsApp কিংবা ফোন কলের নম্বরটি একই হতে হবে। </p>

    </div>
    <p class="text-center mt-5"> <a class="text-sm hover:shadow-md hover:bg-opacity-90 transition duration-300 ease-out-expo text-white shadow-xl rounded-full bg-opacity-80 font-normal py-2 px-3 bg-navy" href="/"> &larr; Back to Market </a></p>
</div>
@endsection