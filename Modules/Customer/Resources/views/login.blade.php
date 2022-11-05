@extends('customer::layouts.auth-master')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-customer-login flex-col">
    <div class="bg-white border border-gray-200 p-8 rounded shadow-xl">
        <h2 class="text-2xl font-bold mb-5 text-gray-800">Login</h2>
        <form class="space-y-3" action="/customer/login" method="POST">
            @csrf
            <div>
                <label class="block mb-1 font-bold text-sm text-gray-500">Mobile Number</label>
                <input required value="{{old('contact')}}" type="number" class="{{$errors->has('contact') ? 'border-red' : 'border-gray-200'}} w-full border-2 border-gray-200 p-2 rounded outline-none focus:border-purple-500" name="contact">
            </div>
            <div>
                <label class="block mb-1 font-bold text-sm text-gray-500">Password</label>
                <input require value="{{old('password')}}" type="password" class="{{$errors->has('password') ? 'border-red' : 'border-gray-200'}} w-full border-2 border-gray-200 p-2 rounded outline-none focus:border-purple-500" name="password">
            </div>
            @if($errors->any())
            @foreach($errors->all() as $error)
            <p class="text-red text-xs inline-block text-bold bg-red py-1 px-2 bg-opacity-10 my-2"> {{ $error }}</p>
            @endforeach
            @endif
            <button class="block w-full bg-blue p-2 shadow-md rounded text-white hover:text-light hover:bg-blue-700 font-bold transition duration-300">Sign In</button>
        </form>
        <!-- Login with facebook  -->
        <!-- <div class="flex w-full space-x-2 mt-5">
            <a href="{{ url('/customer/auth/facebook') }}" class="inline-block bg-white hover:bg-light p-1 rounded shadow-md text-blue-fb border border-gray-200 font-semibold text-sm  transition duration-300">
                <div class="flex w-full  items-center justify-center pr-2">
                    <img src="{{asset('/images/icons/fb.svg')}}" alt="{{asset('/images/icons/fb.svg')}}" class="h-10 w-10 object-cover"> Login with Facebook
                </div>
            </a>
            <a href="{{ url('/customer/auth/google') }}" class="inline-flex bg-white hover:bg-light border border-gray-200 p-1 rounded shadow-md text-gray-500 text-sm font-semibold transition duration-300">
                <div class="flex w-full items-center justify-center pl-1 pr-2">
                    <img src="{{asset('/images/icons/google.svg')}}" alt="{{asset('/images/icons/google.svg')}}" class="h-6 w-6 mr-2 object-cover"> Login with Google
                </div>
            </a>
        </div> -->
        @if(session('message'))
        <p class="text-center mt-5 text-green-600 px-2 rounded py-1 bg-green bg-opacity-10 text-sm font-light"> {{ session('message') }} </p>
        @endif
        <p class="flex text-center mt-5 text-sm font-light">Don't have account? <a class="text-blue font-normal underline ml-1" href="/customer/registration"> Sign up. </a></p>
        <p class="flex text-center mt-5 text-sm font-light"> <a class="text-blue font-normal underline ml-1" href="/customer/forgot-password"> Forgot Password? </a></p>
    </div>
    <p class="text-center mt-5"> <a class="text-sm hover:shadow-md hover:bg-opacity-90 transition duration-300 ease-out-expo text-white shadow-xl rounded-full bg-opacity-80 font-normal py-2 px-3 bg-navy" href="/"> &larr; Back to Market </a></p>
</div>
@endsection