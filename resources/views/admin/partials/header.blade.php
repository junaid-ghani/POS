<div class="header">
    <div class="header-left active">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-normal">
            <img src="{{ asset(config('constants.admin_path').'img/logo.png') }}" alt="">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo logo-white">
            <img src="{{ asset(config('constants.admin_path').'img/logo-white.png') }}" alt="">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo-small">
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
        <li>
            <a href="{{ route('login') }}" class="btn btn-login active" style="padding: 7px 23px;" target="_blank"  >POS</a>
        </li>
        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen">
                <i data-feather="maximize"></i>
            </a>
            
        </li>
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        @if(!empty(Auth::user()->user_image))
                        <img src="{{ asset(config('constants.admin_path').'uploads/profile/'.Auth::user()->user_image) }}" alt="" class="img-fluid">
                        @else
                        <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" alt="" class="img-fluid">
                        @endif
                    </span>
                    <span class="user-detail">
                        <span class="user-name">{{ Auth::user()->user_name }}</span>
                        <span class="user-role">{{ Request()->role->role_name }}</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img">
                            @if(!empty(Auth::user()->user_image))
                            <img src="{{ asset(config('constants.admin_path').'uploads/profile/'.Auth::user()->user_image) }}" alt="">
                            @else
                            <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" alt="">
                            @endif
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{ Auth::user()->user_name }}</h6>
                            <h5>{{ Request()->role->role_name }}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{ route('admin.profile') }}"> <i class="me-2" data-feather="user"></i> My Profile</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{ route('admin.logout') }}"><img src="{{ asset(config('constants.admin_path').'img/icons/log-out.svg') }}" class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('admin.profile') }}">My Profile</a>
            <a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a>
        </div>
    </div>
</div>