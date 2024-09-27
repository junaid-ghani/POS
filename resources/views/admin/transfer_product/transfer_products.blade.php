<form id="transfer_products_form" action="{{ route('admin.transfer_products') }}" method="post">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="new-employee-field">
                <div class="row mb-3">
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">From Location</label>
                            <select name="from_location" id="from_location" class="form-control selectbox" onchange="select_location(); select_transfer_products()">
                                <option value="">Select</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->location_id }}">{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                            <div id="from_location_error"></div>
                            @if ($errors->has('from_location'))
                                <small class="text-danger">{{ $errors->first('from_location') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">To Location</label>
                            <select name="to_location" id="to_location" class="form-control selectbox">
                                <option value="">Select</option>
                            </select>
                            <div id="to_location_error"></div>
                            @if ($errors->has('to_location'))
                                <small class="text-danger">{{ $errors->first('to_location') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-12 col-md-12" id="products_div"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-end mb-3">
        <button type="submit" name="submit" class="btn btn-submit" value="submit">Submit</button>
    </div>
</form>
@section('transfer_product')
    <script>
        function select_transfer_products() {

            var from_location = $('#from_location').val();

            var csrf = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('admin.select_transfer_products') }}",
                type: "post",
                data: "from_location=" + from_location + "&_token=" + csrf,
                success: function(response) {
                    $('#products_div').html(response);
                }
            });
        }

        function select_location() {
            var from_location = $('#from_location').val();

            var csrf = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('admin.select_to_location') }}",
                type: "post",
                data: "from_location=" + from_location + "&_token=" + csrf,
                success: function(response) {
                    $('#to_location').html(response);
                }
            });
        }
    </script>
@endsection
