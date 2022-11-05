<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta name="keywords" content="{{isset($keywords) ? $keywords : null}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="{{ isset($metaData) ? $metaData['title'] . ' | ' . env('APP_NAME')  : env('APP_NAME') . ' | ' .  env('APP_SLOGAN')}}" />
    <meta property="og:description" content="{{ isset($metaData) ? $metaData['description'] : env('APP_NAME') . ' is going to be upcoming one of the most popular e-commerce platform in Bangladesh.'}}" />
    <meta property="og:image" content="{{isset($metaData) ? config('app.url') .'/storage/product_images/' . $metaData['image'] : asset('images/site-thumb.jpg')}}" />
    <link rel="stylesheet" href="{{ asset('css/owlcarousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/owlcarousel/owl.theme.default.min.css')}}">
    <style>
        a {
            color: transparent;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">

    <title>{{ env('APP_NAME') }} | {{ env('APP_SLOGAN') }}</title>
</head>

<body class="bg-light-gray-100 text-dark">
    <div id="app">
        @include('layouts.header')
        <div class="pt-5 pb-20 md:pt-20 md:pb-10 lg:pt-20 lg:pb-20 xl:pt-16 xl:pb-24"></div>
        @include('includes.forms')
        @include('includes.modals')
        @include('layouts.mobile_menu')
        @yield('content')
        @include('layouts.footer')
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>