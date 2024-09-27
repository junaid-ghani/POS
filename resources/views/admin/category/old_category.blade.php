@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | Category')
@section('contents')
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Categories</h4>
                <h6>Manage Categories</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a href="{{ route('admin.category') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="javascript:void(0)" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add_category_modal"><i data-feather="plus-circle" class="me-2"></i>Add Category</a>
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
                <table id="category_table" class="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
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
@section('modal_contents')
<div class="modal fade" id="add_category_modal">
    <div class="modal-dialog modal-dialog-centered custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Add Category</h4>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form id="category_form" action="{{ route('admin.add_category') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="category_name" class="form-control" value="{{ old('category_name') }}" autocomplete="off">
                                @if($errors->has('category_name'))
                                <small class="text-danger">{{ $errors->first('category_name') }}</small>
                                @endif
                            </div>
                            {{-- <div class="mb-3">
                                <label class="form-label">Image</label>
                                <div>
                                    <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" id="category_image_src" class="img-thumbnail" style="height: 150px" alt="">
                                </div>
                                <input type="file" name="category_image" id="category_image" class="form-control">
                                @if($errors->has('category_image'))
                                <small class="text-danger">{{ $errors->first('category_image') }}</small>
                                @endif
                            </div> --}}
                            <div class="modal-footer-btn">
                                <button type="submit" name="submit" class="btn btn-submit" value="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_category_modal">
    <div class="modal-dialog modal-dialog-centered custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Edit Category</h4>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form id="edit_category_form" action="{{ route('admin.edit_category') }}" method="post" enctype="multipart/form-data">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom_script')
<script>
var dataTable =	$('#category_table').DataTable({
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
	          url : "{{ route('admin.get_category') }}",
	          'data': function(data){

	              var token = "{{ csrf_token() }}";

	              data._token = token;

	          }
	    },
	    columns: [
	        {data: 'DT_RowIndex'},
	        {data: 'category_name'},
			{data: 'action',orderable: false, searchable: false}
	    ]
});

function edit_category(category_id)
{
	var csrf = "{{ csrf_token() }}";

	$.ajax({
	    url: "{{ route('admin.get_category_details') }}",
	    type: "post",
	    data: "category_id="+category_id+'&_token='+csrf,
	    success: function (data) {

            $('#edit_category_modal').modal('show');
	        $("#edit_category_form").html(data);
	    }
	});
}

category_image.onchange = evt => {
  const [file] = category_image.files
  if (file) {
    category_image_src.src = URL.createObjectURL(file)
  }
}

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