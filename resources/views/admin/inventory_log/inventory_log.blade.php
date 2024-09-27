<div class="card table-list-card">
    <div class="card-body">
        <div class="table-top row">
            <div class="col-4">
                <div class="search-set">
                    <div class="search-input">
                        <a href="javascript:void(0)" class="btn btn-searchset"><i data-feather="search"
                                class="feather-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-4">

                <div class="card-body pb-0">

                    <div id="inventory_date"
                        style=" cursor: pointer; padding: 8.5px 10px; border: 1px solid #dbe0e6; width: 100%; color:#5B6670; border-radius:5px;">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                    <input type="hidden" value="{{ $today }}" id="ini_start_date">
                    <input type="hidden" value="{{ $today }}" id="ini_end_date">
                </div>
            </div>


        </div>

        <div class="table-responsive ">
            <table id="inventory_log" class="table" style="width: 100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Timestamp</th>
                        <th>Location</th>
                        <th>Submitted By</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Report modal -->
<div class="modal fade" id="Inventory_log_Information" data-bs-backdrop="static">
    <div class="modal-dialog" style="max-width: 820px ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="margin: auto 325px">Inventory Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- {{ route('location.update_stock') }} --}}
                <div class=" d-flex  align-items-center ">
                    <input type="hidden" value="" id="locationInput" name="location_id">
                    <h5 id="location"></h5> <span style="margin:0 3px"> - </span>
                    <h6 id="timestamp"></h6>
                </div>
                <form action="{{ route('admin.fix_inventory_log_item') }}" method="post">
                    @csrf
                    <input type="hidden" id="inventory_id" value="" name="inventory_log_id">
                    <div class="">

                        <div class="input-block mt-3 row ">
                            <div class="col-6">
                                <div class="form-check form-check-md d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="filter_data">
                                    <label class="form-check-label" for="filter_data">Display only items that need attention</label>
                                </div>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <button type="submit" name="submit" value="submit" class="btn btn-primary ">Fix
                                    All</button>
                            </div>
                        </div>

                        <div class="table-responsive  mt-3 ">
                            <table id="inventory_log_item" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Stock</th>
                                        <th>Count</th>
                                        <th>Difference</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Report modal -->


@section('inventory_log')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script type="text/javascript">
        function formatDate(timestamp) {
            var date = new Date(timestamp);

            // Get the individual parts of the date and time
            var day = date.toLocaleString('en-US', {
                day: '2-digit'
            });
            var month = date.toLocaleString('en-US', {
                month: 'short'
            });
            var year = date.toLocaleString('en-US', {
                year: 'numeric'
            });
            var time = date.toLocaleString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // Format the date and time with the comma only after the day
            return `${month} ${day}, ${year} ${time}`;
        }


        //   datepicker end

        var dataTable = $('#inventory_log').DataTable({

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
                $('.dataTables_filter').appendTo('#tableSearch');
                $('.dataTables_filter').appendTo('.search-input');
            },
            processing: true,
            serverSide: true,

            'ajax': {
                type: 'POST',
                url: "{{ route('admin.get_inventory_log') }}",
                'data': function(data) {

                    var token = "{{ csrf_token() }}";
                    var start_date = $('#ini_start_date').val();
                    var end_date = $('#ini_end_date').val();
                    data.end_date = end_date;
                    data.start_date = start_date;
                    data._token = token;
                }
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'timestamp'
                },
                {
                    data: 'location'
                },
                {
                    data: 'submitted_by'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });


        $(document).on('click', '.inventory_action', function() {

            var id = $(this).data('id');
            var location = $(this).data('location');
            var timestamp = $(this).data('timestamp');
            var formated_timestamp = formatDate(timestamp);

            $("#inventory_id").val(id);
            $("#location").text(location);
            $("#locationInput").val(location);
            $("#timestamp").text(formated_timestamp);

            // Destroy and reinitialize DataTable with updated ID
            $('#inventory_log_item').DataTable().clear().destroy();

            // Initialize DataTable with filtering logic
            var log_item = $('#inventory_log_item').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.get_inventory_log_item', '') }}/" + id,
                    type: "GET",
                    data: function(d) {
                        // Pass checkbox state (needing_attention) to server
                        d.needing_attention = $('#filter_data').is(':checked') ? 1 : 0;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false

                    },
                    {
                        data: 'product_name'
                    },
                    {
                        data: 'stock'
                    },
                    {
                        data: 'count'
                    },
                    {
                        data: 'difference'
                    }
                ]
            });
        });

        // Reload DataTable when checkbox state changes
        $('#filter_data').on('change', function() {
            $('#inventory_log_item').DataTable().ajax.reload();
        });

        // --- Salaries Datepicker ---
        $(function() {

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#inventory_date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#inventory_date').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                        .subtract(1, 'month').endOf('month')
                    ]
                }
            }, cb);

            cb(start, end);

            $('#inventory_date').on('apply.daterangepicker', function(ev, picker) {
                let start_date = picker.startDate.format('YYYY-MM-DD');
                let end_date = picker.endDate.format('YYYY-MM-DD');

                // var start = new Date(start_date);
                // var end = new Date(end_date);

                $('#ini_start_date').prop("value", start_date);
                $('#ini_end_date').prop("value", end_date);
                $('#salary_ini_start_date').prop("value", start_date);
                $('#salary_ini_end_date').prop("value", end_date);

                // Trigger salaryDate update
                // $(document).find('#salaryDate').data('daterangepicker').setStartDate(start);
                // $(document).find('#salaryDate').data('daterangepicker').setEndDate(end);

                // $(document).find('#salaryDate span').html(
                // picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY')
                // );
                dataTable.draw();
            });
        });
        // --- End Salaries Datepicker ---
    </script>
@endsection
