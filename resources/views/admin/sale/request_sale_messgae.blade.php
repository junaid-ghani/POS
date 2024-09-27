<head>
    <title>POS System | Request Sale Message </title>
    @include('admin.partials.style')
</head>

<div class="row align-items-center mt-2 mb-5">
    <div class="col-12 d-flex justify-content-center align-items-center">
        @if ($employee_request->status == 1)
            <div class="alert alert-success ">
                Sale has been Approved
            </div>
        @elseif($employee_request->status == 2)
            <div class="alert alert-danger ">
                Sale has been rejected
            </div>
        @endif
    
    </div>
</div>
