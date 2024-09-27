<form id="update_stock_form" action="{{ route('admin.update_stock') }}" method="post">
    @csrf
    <div class="page-header justify-content-end">

        <div class="page-btn">
            <button type="submit" name="submit" class="btn btn-added color " value="submit"><i data-feather="plus-circle"
                    class="me-2"></i>Update Stock</button>
        </div>
    </div>
    <div class="card table-list-card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class=""></div>
                    <div class="search-input">
                        <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                    </div>
                </div>

                <div class="card mb-0" id="filter_inputs" style="display: block">
                    <div class="card-body" style="padding: 0px;">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">

                                <div class="input-blocks" style="margin-bottom:0;">
                                    <i data-feather="home" class="info-img"></i>
                                    <select name="stock_location" id="stock_location" class="form-control selectbox stock_selectbox" onchange="filter_options()">
                                        <option value="">Location</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->location_id }}"
                                                @if ($loop->iteration == 1) selected @endif> {{ $location->location_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="table-responsive">
                <table id="stock_table" class="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="d-none">Location</th>
                            <th>Barcode</th>
                            <th>Category</th>
                            <th>Item Name</th>
                            <th>Stock</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
@section('custom_script')
    <script>        
    
        $(document).ready(function () {
            $(".stock_selectbox").select2({
                placeholder:"Location"
            })
        })
        
        var dataTable = $('#stock_table').DataTable({
            "bFilter": true,
            "sDom": 'fBtlpi',
            "ordering": true,
            "language": {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search",
                info: "_START_ - _END_ of _TOTAL_ items",
                paginate: {
                    next: ' <i class=" fa fa-angle-right"></i>',
                    previous: '<i class="fa fa-angle-left"></i> '
                },
            },
            initComplete: (settings, json) => {
                // $('.dataTables_filter').appendTo('#tableSearch');

                $('.dataTables_filter').appendTo('.search-input');
            },
            "iDisplayLength": 50,
            processing: true,
            serverSide: true,
            'ajax': {
                type: 'POST',
                url: "{{ route('admin.get_stocks') }}",
                'data': function(data) {

                    var token = "{{ csrf_token() }}";
                    var stock_location = $('#stock_location').val();

                    data._token = token;
                    data.stock_location = stock_location;
                }
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                // {data: 'location_name'},
                {
                    data: 'product_code'
                },
                {
                    data: 'category_name'
                },
                {
                    data: 'product_name'
                },
                {
                    data: 'stock_quantity'
                },
                {
                    data: 'qty'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        function filter_options() {
            dataTable.draw();
        }

        function confirm_msg(ev) {
            ev.preventDefault();

            var urlToRedirect = ev.currentTarget.getAttribute('href');

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete it!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1,
                icon: "warning",
            }).then(function(t) {

                if (t.value) {
                    window.location.href = urlToRedirect;
                }
            });
        }
    </script>
@endsection
