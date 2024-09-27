<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="description" content="{{ config('constants.site_title') }}">
<meta name="robots" content="noindex, nofollow">
<title> @yield('title')</title>
@include('location.partials.style')
@yield('custom_style')
</head>
<body class="mini-sidebar" >

<div id="global-loader">
    <div class="whirly-loader"> </div>
</div>
<div class="main-wrapper">
    @include('location.partials.header')
    @include('location.partials.sidebar')
    <div class="page-wrapper">
        @yield('contents')
    </div>
</div>
@include('location.partials.script')
@yield('modal_contents')
@yield('custom_script')

@yield('custom_script2')

@yield('transfer_custom_script')
@yield('customer_custom_script')
</body>
</html>