@extends('layouts.master')

@section('content')
<div class="container mx-auto bg-gray-100">
    <div class="flex justify-center flex-wrap items-start">
        <div class="flex shadow-2xl rounded-md top-14 right-0 transition duration-300 ease-out-expo">
            <div class="bg-white p-8 flex items-center space-x-3 rounded">
                <h2 class="flex text-2xl font-md">Wishlist saved!</h2>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    localStorage.removeItem('wish-list');
</script>