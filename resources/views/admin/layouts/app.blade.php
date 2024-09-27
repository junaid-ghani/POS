<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="description" content="{{ config('constants.site_title') }}">
<meta name="robots" content="noindex, nofollow">
<title>@yield('title')</title>
@include('admin.partials.style')
@yield('custom_style')
</head>
<body>
<div id="global-loader">
    <div class="whirly-loader"> </div>
</div>
<div class="main-wrapper">
    @include('admin.partials.header')
    @include('admin.partials.sidebar')
    <div class="page-wrapper">
        @yield('contents')
    </div>
    @yield('modal_contents')
</div>
@include('admin.partials.script')
@yield('custom_script')
@yield('custom_script2')

@yield('transfer_product')
@yield('assign_product')
@yield('inventory_log')
@yield('category_custom_script')
@yield('product_custom_script')
@yield('user_custom_script')
@yield('location_custom_script')
@yield('customer_custom_script')


</body>
</html>