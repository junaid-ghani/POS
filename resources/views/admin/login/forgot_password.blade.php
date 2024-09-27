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
                <form id="forgot_password_form" action="{{ route('admin_forgot_password') }}" method="post">
                    @csrf
                    <div class="login-userset">
                        <div class="login-logo logo-normal">
                            <img src="{{ asset(config('constants.admin_path').'img/logo.png') }}" alt="img">
                        </div>
                        <a href="javascript:void(0)" class="login-logo logo-white">
                            <img src="{{ asset(config('constants.admin_path').'img/logo-white.png') }}"  alt="">
                        </a>
                        <div class="login-userheading">
                            <h3>Forgot password?</h3>
                            <h4>Please enter your email address to get the password.</h4>
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
                        <div class="form-login">
                            <button type="submit" name="submit" class="btn btn-login" value="submit">Submit</button>
                        </div>
                        <div class="signinform text-center">
                            <h4>Return to<a href="{{ route('admin') }}" class="hover-a"> login </a></h4>
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