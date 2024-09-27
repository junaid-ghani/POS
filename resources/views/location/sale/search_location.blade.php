@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | Create Sale')
@section('contents')
<div class="content pos-design p-0">
    <div class="btn-row d-sm-flex align-items-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                   <div class="card-title">Search Location</div>
                </div>
                <div class="card-body">
                    <form id="search_location_form" action="{{ route('admin.search_location') }}" class="row gx-3 gy-2 align-items-center mt-0" method="post">
                        @csrf
                        <div class="col-sm-6">
                         <label class="visually-hidden" for="specificSizeSelect">Location</label>
                         <select name="location" class="form-select selectbox_location" id="specificSizeSelect">
                            <option value="">Location</option>
                            @foreach($locations as $location)
                            <option value="{{ $location->location_id }}">{{ $location->location_name }}</option>
                            @endforeach
                         </select>
                         <div id="location_error"></div>
                        </div>
                        <div class="col-auto">
                         <button type="submit" name="submit" class="btn btn-primary" value="search">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom_script')
<script>
$(".selectbox_location").select2({
    placeholder: "Location"
});    
</script>
@endsection