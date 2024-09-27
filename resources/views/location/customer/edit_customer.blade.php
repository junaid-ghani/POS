{{-- @extends('location.layouts.app')
@section('title',config('constants.site_title').' | Edit Customer')
@section('contents') --}}
<style>
    span.select2.select2-container.select2-container--default,span.select2.select2-container.select2-container--default.select2-container--above,span.select2.select2-container.select2-container--default.select2-container--above.select2-container--focus.select2-container--open{

        width:0px !important;
    }
</style>
{{-- <div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Customers</h4>
                <h6>Edit Customer</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('location.customers') }}" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div> --}}
        @csrf
        {{-- @dd($customer_seller) --}}
        <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}" >
        <div class="card">
            <div class="card-body">
                <div class="new-employee-field">
                    {{-- <div class="card-title-head">
                        <h6><span><i data-feather="info" class="feather-edit"></i></span>Customer Information</h6>
                    </div> --}}
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Customer Name</label>
                                <input type="text" name="customer_name" class="form-control" value="{{ $customer->customer_name }}" autocomplete="off">
                                @if($errors->has('customer_name'))
                                <small class="text-danger">{{ $errors->first('customer_name') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="customer_email" class="form-control" value="{{ $customer->customer_email }}" autocomplete="off">
                                @if($errors->has('customer_email'))
                                <small class="text-danger">{{ $errors->first('customer_email') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="customer_phone" class="form-control phone_format_validate" value="{{ $customer->customer_phone }}" autocomplete="off">
                                @if($errors->has('customer_phone'))
                                <small class="text-danger">{{ $errors->first('customer_phone') }}</small>
                                @endif
                            </div>
                        </div>
                        @php
                            $customer_seller_ids = App\Models\CustomerSeller::where('customer_id', $customer->customer_id)->pluck('seller_id')->toArray();
                        @endphp
                        
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label">Salesperson</label>
                                <select name="sale_sellers[]" class="form-control selectbox_seller " multiple>
                                    <option value="">Search Salesperson</option>
                                    @foreach($sellers as $seller)
                                        <option 
                                            value="{{ $seller->user_id }}" 
                                            {{ in_array($seller->user_id, $customer_seller_ids) ? 'selected' : '' }}>
                                            {{ $seller->user_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    
                        {{-- <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select name="customer_gender" class="form-control selectbox">
                                    <option value="">Select</option>
                                    <option value="1" {{ $customer->customer_gender == 1 ? 'selected':'' }}>Male</option>
                                    <option value="2" {{ $customer->customer_gender == 2 ? 'selected':'' }}>Female</option>
                                </select>
                                <div id="customer_gender_error"></div>
                                @if($errors->has('customer_gender'))
                                <small class="text-danger">{{ $errors->first('customer_gender') }}</small>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="customer_dob" class="form-control" value="{{ $customer->customer_dob }}" autocomplete="off">
                                @if($errors->has('customer_dob'))
                                <small class="text-danger">{{ $errors->first('customer_dob') }}</small>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="customer_address" class="form-control" autocomplete="off">{{ $customer->customer_address }}</textarea>
                                @if($errors->has('customer_address'))
                                <small class="text-danger">{{ $errors->first('customer_address') }}</small>
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
{{-- </div>

@endsection --}}
{{-- @section('custom_script') --}}
<script>
    $(".selectbox_seller").select2({
        placeholder: "Search Salesperson"
    });

    
</script>
{{-- @endsection --}}
