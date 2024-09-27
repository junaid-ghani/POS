@extends('location.layouts.app')
@section('title',config('constants.site_title').' | Profile')
@section('contents')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Profile</h4>
            <h6>Location Profile</h6>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form id="profile_form" action="{{ route('location.profile') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="profile-set">
                    <div class="profile-head"></div>
                    <div class="profile-top">
                        <div class="profile-content">
                            <div class="profile-contentimg">
                                @if(!empty(Auth::guard('location')->user()->location_image))
                                <img src="{{ asset(config('constants.admin_path').'uploads/location/'.Auth::guard('location')->user()->location_image) }}" alt="img" id="blah">
                                @else
                                <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" alt="img" id="blah">
                                @endif
                                <div class="profileupload">
                                    <input type="file" id="imgInp" name="location_image" accept="image/*">
                                    <a href="javascript:void(0)"><img src="{{ asset(config('constants.admin_path').'img/icons/edit-set.svg') }}"  alt="img"></a>
                                </div>
                            </div>
                            <div class="profile-contentname">
                                <h2>{{ Auth::guard('location')->user()->location_name }}</h2>
                                <h4>Location</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Name</label>
                            <input type="text" name="location_name" class="form-control" value="{{ Auth::guard('location')->user()->location_name }}" autocomplete="off">
                            @if($errors->has('location_name'))
                            <span class="text-danger">{{ $errors->first('location_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Tax (%)</label>
                            <input type="text" name="location_tax" value="{{ Auth::guard('location')->user()->location_tax }}" autocomplete="off">
                            @if($errors->has('location_tax'))
                            <span class="text-danger">{{ $errors->first('location_tax') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Password</label>
                            <div class="pass-group">
                                <input type="password" name="location_password" class="pass-input form-control" value="{{ base64_decode(Auth::guard('location')->user()->location_vpassword) }}" autocomplete="off">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                            @if($errors->has('location_password'))
                            <span class="text-danger">{{ $errors->first('location_password') }}</span>
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