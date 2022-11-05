@extends('customer::layouts.auth-master')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-customer-login flex-col">
    <div class="bg-white p-8 rounded border border-gray-200 shadow-xl w-full md:w-2/3 lg:w-2/3 xl:w-1/3">
        <h2 class="text-2xl font-bold mb-5 text-gray-800">Create Your Account</h2>
        <form class="space-y-3" action="/customer/registration" method="POST">
            @csrf
            <div>
                <label class="block mb-1 font-bold text-sm text-gray-500">Name</label>
                <input value="{{old('name')}}" type="text" class="{{$errors->has('name') ? 'border-red' : 'border-gray-200'}} w-full border-2 p-2 rounded outline-none focus:border-purple-500" name="name">
            </div>
            <div>
                <label class="block mb-1 font-bold text-sm text-gray-500">Contact No</label>
                <input value="{{old('contact')}}" type="number" min="0" class="{{$errors->has('contact') ? 'border-red' : 'border-gray-200'}} w-full border-2 border-gray-200 p-2 rounded outline-none focus:border-purple-500" name="contact">
            </div>
            <div>
                <label class="block mb-1 font-bold text-sm text-gray-500">Password</label>
                <input value="{{old('password')}}" type="password" class="{{$errors->has('password') ? 'border-red' : 'border-gray-200'}} w-full border-2 border-gray-200 p-2 rounded outline-none focus:border-purple-500" name="password">
            </div>
            <div>
                <label class="block mb-1 font-bold text-sm text-gray-500">Confirm Password</label>
                <input value="{{old('password_confirmation')}}" type="password" class="w-full border-2 border-gray-200 p-2 rounded outline-none focus:border-purple-500" name="password_confirmation">
            </div>
            <div class="flex items-center">
                <input {{ old('agree') == 'on' ? 'checked' : '' }} type="checkbox" id="agree" name="agree">
                <label for="agree" class="ml-2 text-gray-700 text-xs">I agree to the terms and privacy policy.</label>
            </div>
            @if($errors->any())
            @foreach($errors->all() as $error)
            <p class="text-red text-xs inline-block text-bold bg-red py-1 px-2 bg-opacity-10 my-2"> {{ $error }}</p>
            @endforeach
            @endif
            <button class="block w-full bg-blue p-2 shadow-md rounded text-white hover:text-light hover:bg-blue-700 font-bold transition duration-300">Sign Up</button>
        </form>
        <p class="text-center mt-5 text-sm font-light">Already have an account? <a class="text-blue font-normal underline" href="/customer/login"> Login instead. </a></p>
    </div>
    <p class="text-center mt-5"> <a class="text-sm hover:shadow-md hover:bg-opacity-90 transition duration-300 ease-out-expo text-white shadow-xl rounded-full bg-opacity-80 font-normal py-2 px-3 bg-navy" href="/"> &larr; Back to Market </a></p>
</div>
@endsection