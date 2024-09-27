@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | Edit Location')
@section('contents')
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Locations</h4>
                <h6>Edit Location</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('admin.locations') }}" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>
    <form id="location_form" action="{{ route('admin.edit_location',['id'=>Request()->segment(3)]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="new-employee-field">
                    <div class="card-title-head">
                        <h6><span><i data-feather="info" class="feather-edit"></i></span>Location Information</h6>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Location Name</label>
                                <input type="text" name="location_name" class="form-control" value="{{ $location->location_name }}" autocomplete="off">
                                @if($errors->has('location_name'))
                                <small class="text-danger">{{ $errors->first('location_name') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tax (%)</label>
                                <input type="number" name="location_tax" class="form-control decimals" value="{{ $location->location_tax }}" autocomplete="off">
                                @if($errors->has('location_tax'))
                                <small class="text-danger">{{ $errors->first('location_tax') }}</small>
                                @endif
                            </div>
                        </div>
                        
                                            
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="location_address" class="form-control" value="{{ $location->location_address }}" autocomplete="off">
                                @if($errors->has('location_address'))
                                <small class="text-danger">{{ $errors->first('location_address') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="location_city" class="form-control" value="{{ $location->location_city }}" autocomplete="off">
                                @if($errors->has('location_city'))
                                <small class="text-danger">{{ $errors->first('location_city') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="location_state" class="form-control" value="{{ $location->location_state}}" autocomplete="off">
                                @if($errors->has('location_state'))
                                <small class="text-danger">{{ $errors->first('location_state') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">ZIP </label>
                                <input type="number" name="location_zip" class="form-control" value="{{ $location->location_zip }}" autocomplete="off">
                                @if($errors->has('location_zip'))
                                <small class="text-danger">{{ $errors->first('location_zip') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="location_phone_number" class="form-control phone_format_validate" value="{{ $location->location_phone_number }}" autocomplete="off">
                                {{-- <small id="phoneError" style="color: red; display: none;">Phone number format should be XXX-XXX-XXXX.</small> --}}
                                @if($errors->has('location_phone_number'))
                                <small class="text-danger">{{ $errors->first('location_phone_number') }}</small>
                                @endif
                            </div>
                        </div>



                        {{-- <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <div>
                                    @if(!empty($location->location_image))
                                    <img src="{{ asset(config('constants.admin_path').'uploads/location/'.$location->location_image) }}" class="img-thumbnail" id="location_image_src" style="height: 150px" alt="">
                                    @else
                                    <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" class="img-thumbnail" id="location_image_src" style="height: 150px" alt="">
                                    @endif
                                </div>
                                <input type="file" name="location_image" id="location_image" class="form-control">
                                @if($errors->has('location_image'))
                                <small class="text-danger">{{ $errors->first('location_image') }}</small>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-6 col-md-6">
                            <div class="input-blocks mb-md-0 mb-sm-3">
                                <label>Password</label>
                                <div class="pass-group">
                                    <input type="password" name="location_password" class="pass-input" value="{{ base64_decode($location->location_vpassword) }}" autocomplete="off">
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                                @if($errors->has('location_password'))
                                <small class="text-danger">{{ $errors->first('location_password') }}</small>
                                @endif
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end mb-3">
            <button type="submit" name="submit" class="btn btn-submit" value="submit">Save</button>
        </div>
    </form>
</div>
@endsection
@section('custom_script')
<script>
$(document).ready(function() {
    $('.decimals').on('input', function() {
        var value = $(this).val();
        if (value !== '') {
            var floatValue = parseFloat(value);
            if (!isNaN(floatValue)) {
                $(this).blur(function () {
                    $(this).val(floatValue.toFixed(2));
                })
            }
        }
    });
});

location_image.onchange = evt => {
  const [file] = location_image.files
  if (file) {
    location_image_src.src = URL.createObjectURL(file)
  }
}
</script>
@endsection