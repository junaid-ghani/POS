@extends('admin.layouts.app')
@section('title', config('constants.site_title') . ' | Locations')
@section('contents')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Locations</h4>
                    <h6>Manage Locations</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a href="{{ route('admin.locations') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i
                            data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
            @if ($user->user_role == '1')
                <div class="page-btn">
                    {{-- {{ route('admin.add_location') }}  --}}
                    <a href="javascript:joid(0)" data-bs-toggle="modal" data-bs-target="#add_location_modal"
                        class="btn btn-added">
                        <i data-feather="plus-circle" class="me-2"></i> Create Location
                    </a>
                </div>
            @endif

        </div>
        <div class="card table-list-card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                    class="feather-search"></i></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="location_table" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Location Name</th>
                                <th>Address</th>
                                <th>City , State</th>
                                {{-- <th>State</th> --}}
                                <th>Phone Number</th>
                                <th>Tax (%)</th>
                                {{-- <th>ZIP</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="add_location_modal">
            <div class="modal-dialog modal-dialog-centered custom-modal-two" style="max-width: 1000px;">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Create Location</h4>
                                </div>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body custom-modal-body">
                                @include('admin.location.add_location')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="edit_location_modal">
            <div class="modal-dialog modal-dialog-centered custom-modal-two" style="max-width: 1000px;">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Edit Location</h4>
                                </div>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body custom-modal-body">
                                <form id="edit_location_form" action="{{ route('admin.edit_location') }}" method="post"enctype="multipart/form-data">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('custom_script')
    <script>
        var dataTable = $('#location_table').DataTable({
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
                url: "{{ route('admin.get_locations') }}",
                'data': function(data) {

                    var token = "{{ csrf_token() }}";

                    data._token = token;

                }
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'location_name'
                },
                {
                    data: 'location_address'
                },
                {
                    data: 'City, State'
                },
                // {data: 'location_state'},
                {
                    data: 'location_phone_number'
                },
                {
                    data: 'location_tax'
                },
                // {data: 'location_zip'},
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        function edit_location(location_id) {
            var csrf = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('admin.view_edit_location') }}",
                type: "post",
                data: "location_id=" + location_id + '&_token=' + csrf,
                success: function(data2) {

                    $('#edit_location_modal').modal('show');
                    $("#edit_location_form").html(data2);
                }
            });
        }

        function confirm_msg(ev) {
            ev.preventDefault();

            var urlToRedirect = ev.currentTarget.getAttribute('href');

            Swal.fire({
                title: "Are you sure?",
                text: "You want to change status!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, change it!",
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
