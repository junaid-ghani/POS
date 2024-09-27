<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="description" content="{{ config('constants.site_title') }}">
<meta name="robots" content="noindex, nofollow">
<title>{{ config('constants.site_title') }}</title>
<link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('constants.admin_path').'img/favicon.png') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'plugins/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'css/style.css') }}">
</head>
<body class="account-page">
<div id="global-loader" >
    <div class="whirly-loader"> </div>
</div>
<div class="main-wrapper">
    <div class="account-content">
     
        <div class="login-wrapper bg-img">
               
            <div class="login-content">                
                <div style="position: absolute;top: 10px;left: 10px;" > <a href="{{ route('admin') }}" class="btn btn-login active" target="blank" >User Login</a> </div>             
                <form id="login_form" action="{{ route('get_index') }}" method="post">
                    @csrf
                    <div class="login-userset">
                        <div class="login-logo logo-normal">
                            <img src="{{ asset(config('constants.admin_path').'img/logo.png') }}" alt="img">
                        </div>
                        <a href="javascript:void(0)" class="login-logo logo-white">
                            <img src="{{ asset(config('constants.admin_path').'img/logo-white.png') }}"  alt="">
                        </a>
                        <div class="login-userheading">
                            <h3>Sign In</h3>
                            <h4>Access the {{ config('constants.site_title') }} panel using your email and passcode.</h4>
                        </div>
                        <div class="form-login mb-3">
                            <label class="form-label">Location</label>
                            <select name="location_name" id="location_name" class="form-control selectbox">
                                <option value="">Select</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->location_id }}">{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                            <small id="location_name_error"></small>
                            @if($errors->has('location_name'))
                            <small class="text-danger">{{ $errors->first('location_name') }}</small>
                            @endif
                        </div>
                        {{-- <div class="form-login mb-3">
                            <div class="pass-group">
                                <label class="form-label">Remember location on this device</label>
                                <input type="checkbox" name="remember" class="form-check-input" value="">
                            </div>                        
                        </div> --}}
                        <div class="form-check form-check-md d-flex align-items-center mb-3">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember location on this device</label>
                        </div>
                        {{-- <div class="form-login mb-3">
                            <label class="form-label">Password</label>
                            <div class="pass-group">
                                <input type="password" name="location_password" class="pass-input form-control" value="{{ old('location_password') }}" autocomplete="off">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                            @if($errors->has('location_password'))
                            <small class="text-danger">{{ $errors->first('location_password') }}</small>
                            @endif
                        </div> --}}
                        <div class="form-login">
                            <button type="submit" name="submit" class="btn btn-login" value="submit">Sign In</button>
                        </div>
                        <div class="form-sociallink">
                            <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                                <p>Copyright &copy; {{ date('Y') }} {{ config('constants.site_title') }}. All rights reserved</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="customizer-links" id="setdata">
    <ul class="sticky-sidebar">
        <li class="sidebar-icons">
            <a href="#" class="navigation-add" data-bs-toggle="tooltip" data-bs-placement="left"
                data-bs-original-title="Theme">
                <i data-feather="settings" class="feather-five"></i>
            </a>
        </li>
    </ul>
</div>
<script src="{{ asset(config('constants.admin_path').'js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/feather.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/jquery.validate.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/location_form_validations.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/script.js') }}"></script>
<script>
$(".selectbox").select2({
	placeholder: "Select",
	allowClear: true,
	dir: "ltr"
});

@if(Session::has('success'))
Swal.fire({
    title: "Good job!",
    text: "{{ Session::get('success') }}",
    type: "success",
    confirmButtonClass: "btn btn-primary",
    buttonsStyling: !1,
    icon: "success"
});
@endif
@if(Session::has('error'))
Swal.fire({
    title: "Warning!",
    text: "{{ Session::get('error') }}",
    type: "warning",
    confirmButtonClass: "btn btn-primary",
    buttonsStyling: !1,
    icon: "warning"
});
@endif
</script>
</body>
</html>