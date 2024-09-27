@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | Products')
@section('contents')
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Products</h4>
                <h6>Manage Products</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a href="{{ route('admin.products') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="{{ route('admin.add_product') }}" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Add Product</a>
        </div>
    </div>
    <div class="card table-list-card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="product_table" class="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Barcode</th>
                            <th>Cost Price</th>
                            <th>Minimum Price</th>
                            <th>Retail Price</th>
                            <th>Priority</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom_script')
<script>
var dataTable =	$('#product_table').DataTable({
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
        initComplete: (settings, json)=>{
            $('.dataTables_filter').appendTo('#tableSearch');
            $('.dataTables_filter').appendTo('.search-input');
		},	
	    processing: true,
	    serverSide: true,
	    'ajax': {
	          type : 'POST',
	          url : "{{ route('admin.get_products') }}",
	          'data': function(data){

	              var token = "{{ csrf_token() }}";

	              data._token = token;
	          }
	    },
	    columns: [
	        {data: 'DT_RowIndex'},
	        {data: 'product_name'},
            {data: 'category_name'},
            {data: 'product_code'},
            {data: 'product_cost_price'},
            {data: 'product_min_price'},
            {data: 'product_retail_price'},
            {data: 'priority'},
			{data: 'action',orderable: false, searchable: false}
	    ]
});

function confirm_msg(ev)
{
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
    }).then(function (t) {
            
        if(t.value)
        {
            window.location.href = urlToRedirect;
        }
    });
}
</script>
@endsection