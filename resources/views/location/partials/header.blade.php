<div class="header">
    <div class="header-left active">
        <a href="{{ route('location.create_sale',Auth::guard('location')->id()) }}" class="logo logo-normal">
            <img src="{{ asset(config('constants.admin_path').'img/logo.png') }}" alt="">
        </a>
        <a href="{{ route('location.create_sale',Auth::guard('location')->id()) }}" class="logo logo-white">
            <img src="{{ asset(config('constants.admin_path').'img/logo-white.png') }}" alt="">
        </a>
        <a href="{{ route('location.create_sale',Auth::guard('location')->id()) }}" class="logo-small">
            <img src="{{ asset(config('constants.admin_path').'img/logo-small.png') }}" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>
    <ul class="nav user-menu">
        <li class="nav-item nav-searchinputs"></li>
        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen">
                <i data-feather="maximize"></i>
            </a>
        </li>
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    {{-- <span class="user-letter">
                        @if(!empty(Auth::guard('location')->user()->location_image))
                        <img src="{{ asset(config('constants.admin_path').'uploads/location/'.Auth::guard('location')->user()->location_image) }}" alt="" class="img-fluid">
                        @else
                        <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" alt="" class="img-fluid">
                        @endif
                    </span> --}}
                    <span class="user-detail">
                        <span class="user-name">{{ Auth::guard('location')->user()->location_name }}</span>
                        {{-- <span class="user-role">Location</span> --}}
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    {{-- <div class="profileset">
                        <span class="user-img  ">
                            @if(!empty(Auth::guard('location')->user()->location_image))
                            <img src="{{ asset(config('constants.admin_path').'uploads/location/'.Auth::guard('location')->user()->location_image) }}" alt="">
                            @else
                            <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" alt="">
                            @endif
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{ Auth::guard('location')->user()->location_name }}</h6>
                            <h5>Location</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{ route('location.profile') }}"> <i class="me-2" data-feather="user"></i> My Profile</a>
                    <hr class="m-0"> --}}
                    <a class="dropdown-item logout pb-0" onclick="remove_localstorage_item_and_logout()" ><img src="{{ asset(config('constants.admin_path').'img/icons/log-out.svg') }}" class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('location.profile') }}">My Profile</a>
            <a class="dropdown-item" onclick="remove_localstorage_item_and_logout()" > Logout</a>
        </div>
    </div>
</div>

<script>
    function remove_localstorage_item_and_logout(){
		
		localStorage.removeItem('screenModeNightTokenState', 'night');
		window.location.href="{{ route('location.logout') }}";
	}
</script>