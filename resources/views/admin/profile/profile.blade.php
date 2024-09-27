@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | Profile')
@section('contents')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Profile</h4>
            <h6>User Profile</h6>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form id="profile_form" action="{{ route('admin.profile') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="profile-set">
                    <div class="profile-head"></div>
                    <div class="profile-top">
                        <div class="profile-content">
                            <div class="profile-contentimg">
                                @if(!empty(Auth::user()->user_image))
                                <img src="{{ asset(config('constants.admin_path').'uploads/profile/'.Auth::user()->user_image) }}" alt="img" id="blah">
                                @else
                                <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" alt="img" id="blah">
                                @endif
                                <div class="profileupload">
                                    <input type="file" id="imgInp" name="user_image" accept="image/*">
                                    <a href="javascript:void(0)"><img src="{{ asset(config('constants.admin_path').'img/icons/edit-set.svg') }}"  alt="img"></a>
                                </div>
                            </div>
                            <div class="profile-contentname">
                                <h2>{{ Auth::user()->user_name }}</h2>
                                <h4>{{ Request()->role->role_name }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Name</label>
                            <input type="text" name="user_name" class="form-control" value="{{ Auth::user()->user_name }}" autocomplete="off">
                            @if($errors->has('user_name'))
                            <span class="text-danger">{{ $errors->first('user_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label>Email</label>
                            <input type="email" name="user_email" class="form-control" value="{{ Auth::user()->user_email }}" autocomplete="off">
                            @if($errors->has('user_email'))
                            <span class="text-danger">{{ $errors->first('user_email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Phone</label>
                            <input type="text" name="user_phone" value="{{ Auth::user()->user_phone }}" autocomplete="off">
                            @if($errors->has('user_phone'))
                            <span class="text-danger">{{ $errors->first('user_phone') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Password</label>
                            <div class="pass-group">
                                <input type="password" name="user_password" class="pass-input form-control" value="{{ base64_decode(Auth::user()->user_vpassword) }}" autocomplete="off">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                            @if($errors->has('user_password'))
                            <span class="text-danger">{{ $errors->first('user_password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="submit" class="btn btn-submit me-2" value="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection