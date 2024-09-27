{{-- <div class="page-header">
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
    </div> --}}
<form id="assign_products_form" action="{{ route('admin.assign_products') }}" method="post">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="new-employee-field">
                <div class="row mb-3">
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                          
                            <label class="form-label">Choose </label>
                            <select name="stock_location" id="stock_location" class="form-control selectbox">
                                <option value="">Select</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->location_id }}">{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                            <div id="stock_location_error"></div>
                            @if ($errors->has('stock_location'))
                                <small class="text-danger">{{ $errors->first('stock_location') }}</small>
                            @endif
                        </div>
                    </div>
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

@section('assign_product')
    <script>

        $(document).ready(function () {
        
            $(".selectbox").select2({
                placeholder:"Select"
            })
        })      

        $(document).on("change","#stock_location",function () {
           
            var location_id = $(this).val();     

            if (location_id != '') {
                var csrf = "{{ csrf_token() }}";

                $.ajax({
                    url: "{{ route('admin.select_products') }}",
                    type: "post",
                    data: "location_id=" + location_id + "&_token=" + csrf,
                    success: function(response) {
                     
                        $('#stock_product').html(response);
                    }
                });
            }
        })

    </script>
@endsection
