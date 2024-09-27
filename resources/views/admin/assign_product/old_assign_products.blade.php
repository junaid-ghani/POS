@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | Assign Products')
@section('contents')
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Assign Products</h4>
                <h6>Add Assign Products</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>
    <form id="assign_products_form" action="{{ route('admin.assign_products') }}" method="post">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="new-employee-field">
                    {{-- <div class="card-title-head">
                        <h6><span><i data-feather="info" class="feather-edit"></i></span>Assign Products Information</h6>
                    </div> --}}
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Choose Location</label>
                                <select name="stock_location" id="stock_location" class="form-control selectbox" onchange="select_products()">
                                    <option value="">Select</option>
                                    @foreach($locations as $location)
                                    <option value="{{ $location->location_id }}">{{ $location->location_name }}</option>
                                    @endforeach
                                </select>
                                <div id="stock_location_error"></div>
                                @if($errors->has('stock_location'))
                                <small class="text-danger">{{ $errors->first('stock_location') }}</small>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="stock_category" id="stock_category" class="form-control selectbox" onchange="select_products()">
                                    <option value="">Select</option>
                                    @foreach($category_list as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                <div id="stock_category_error"></div>
                                @if($errors->has('stock_category'))
                                <small class="text-danger">{{ $errors->first('stock_category') }}</small>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Product</label>
                                <select name="stock_product[]" id="stock_product" class="form-control selectbox" multiple>
                                    <option value="">Select</option>
                                </select>
                                <div id="stock_product_error"></div>
                                @if($errors->has('stock_product'))
                                <small class="text-danger">{{ $errors->first('stock_product') }}</small>
                                @endif
                            </div>
                        </div> --}}
                        <div class="row mb-3">
                            <div class="col-lg-12 col-md-12" id="stock_product"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end mb-3">
            <button type="submit" name="submit" class="btn btn-submit" value="submit">Assign</button>
        </div>
    </form>
</div>
@endsection
@section('custom_script')
<script>
// function select_products()
// {
//     var location_id = $('#stock_location').val();
//     var category_id = $('#stock_category').val();

//     if(location_id != '' && category_id != '')
//     {
//         var csrf = "{{ csrf_token() }}";

//         $.ajax({
//             url: "{{ route('admin.select_products') }}",
//             type: "post",
//             data: "location_id="+location_id+"&category_id="+category_id+"&_token="+csrf,
//             success: function (response) 
//             {
//                 $('#stock_product').html(response);
//             }
//         });
//     }
// }

function select_products()
{
    var location_id = $('#stock_location').val();
    // var category_id = $('#stock_category').val();

    if(location_id != '')
    {
        var csrf = "{{ csrf_token() }}";

        $.ajax({
            url: "{{ route('admin.select_products') }}",
            type: "post",
            data: "location_id="+location_id+"&_token="+csrf,
            success: function (response) 
            {
                $('#stock_product').html(response);
            }
        });
    }
}
</script>
@endsection