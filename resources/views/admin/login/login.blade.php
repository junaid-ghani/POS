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
                <form id="login_form" action="{{ route('admin') }}" method="post">
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
                            <h4>Access the {{ config('constants.site_title') }} panel using your email and password.</h4>
                        </div>
                        <div class="form-login mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="form-addons">
                                <input type="text" name="user_email" class="form- control" value="{{ old('user_email') }}" autocomplete="off">
                                <img src="{{ asset(config('constants.admin_path').'img/icons/mail.svg') }}" alt="img">
                            </div>
                            @if($errors->has('user_email'))
                            <small class="text-danger">{{ $errors->first('user_email') }}</small>
                            @endif
                        </div>
                        <div class="form-login mb-3">
                            <label class="form-label">Password</label>
                            <div class="pass-group">
                                <input type="password" name="user_password" class="pass-input form-control" value="{{ old('user_password') }}" autocomplete="off">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                            @if($errors->has('user_password'))
                            <small class="text-danger">{{ $errors->first('user_password') }}</small>
                            @endif
                        </div>
                        <div class="form-login authentication-check">
                            <div class="row">
                                <div class="col-12 d-flex align-items-center justify-content-between">
                                    <div class="custom-control custom-checkbox"></div>
                                    <div class="text-end">
                                        <a class="forgot-link" href="{{ route('admin_forgot_password') }}">Forgot Password?</a>
                                    </div>
                                </div>                                    
                            </div>
                        </div>
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
<script src="{{ asset(config('constants.admin_path').'js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/feather.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/jquery.validate.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/form_validations.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/script.js') }}"></script>
<script>
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