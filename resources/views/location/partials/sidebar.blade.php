
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <ul>
                        {{-- <li><a href="{{ route('location.dashboard') }}" @if (isset($set) && $set == 'dashboard') class="active" @endif><i data-feather="box"></i><span>Dashboard</span></a></li> --}}

                        <li><a href="{{ route('location.create_sale', Auth::guard('location')->id()) }}"
                                @if (isset($set) && $set == 'create_sale') class="active" @endif><i
                                    data-feather="box"></i><span>Home</span></a></li>
                        {{-- <li><a href="{{ route('location.sales') }}" @if (isset($set) && $set == 'sales') class="active" @endif><i data-feather="package"></i><span>Transactions</span> </a></li> --}}
                        <li class="add_route" data-Current_route="{{ route('location.sales') }}"><a
                                href="javascript:void(0)"
                                @if (isset($set) && $set == 'sales') class="active " @else data-bs-toggle="modal" data-bs-target="#checkPinModal" @endif><i
                                    data-feather="shopping-cart"></i><span>Transactions</span></a></li>
                        {{-- <li class="submenu">
                            <a href="{{ route('location.stocks') }}" class="subdrop @if (isset($set) && ($set == 'assign_products' || $set == 'transfer_products' || $set == 'stocks')) active @endif">  <i data-feather="package"></i><span>Inventory</span><span class="menu-arrow"></span></a>
                            <ul>
                                
                                <li><a href="{{ route('location.stocks') }}" @if (isset($set) && $set == 'stocks') class="active" @endif>Manage Inventory</a></li>
                                <li><a href="{{ route('location.transfer_products') }}" @if (isset($set) && $set == 'transfer_products') class="active" @endif>Transfers</a></li>
                            </ul>
                        </li> --}}

                        <li  class="add_route" data-Current_route="{{ route('location.stocks') }}" ><a href="{{ route('location.stocks') }}"
                                @if ((isset($set) && $set == 'stocks') || $set == 'transfer_products') class="active" @else data-bs-toggle="modal" data-bs-target="#checkPinModal"  @endif ><i data-feather="package"></i>
                                <span>Inventory</span> </a></li>

                        <li><a href="{{ route('location.customers') }}"
                                @if (isset($set) && $set == 'customers') class="active" @endif><i
                                    data-feather="user"></i><span>Customers</span></a></li>
                        <li class="add_route" data-Current_route="{{ route('location.summery') }}"><a
                                href="javascript:void(0)"
                                @if (isset($set) && $set == 'summery') class="active " @else data-bs-toggle="modal" data-bs-target="#checkPinModal" @endif><i
                                    data-feather="file-text"></i><span>End of Day Summary</span></a></li>

                    </ul>

                </li>
            </ul>
        </div>
    </div>
</div>

@section('modal_contents')
    <div class="modal fade" id="checkPinModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none" id="pinError"></div>
                    <form action="{{ route('location.location_verify_pin') }}" method="POST" id="location_verify_pin">
                        @csrf
                        <input type="hidden" name="current_route" id="current_route" value="">
                        <div class="form-group">
                            <label>Enter PIN *</label>
                            <input type="number" name="pin" id="user_pin" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary" id="verifyBtn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script2')
    <script>
        localStorage.setItem('verifyButtonId', $('#user_pin').attr('id'));

        $(".add_route").on("click", function () {

            let currentRoute = $(this).attr('data-Current_route');
            $("#current_route").val(currentRoute);
            var segments = currentRoute.split('/');
            var action = segments[6];
                       
            if(action == 'sales'){
                $("#staticBackdropLabel").text('Transactions');
            }
            if(action == 'stocks'){
                $("#staticBackdropLabel").text('Inventory');
            }
            if (action == 'summery') {
                $("#staticBackdropLabel").text('End of Day Summary');       
            }
        })
        $('#location_verify_pin').submit(function(e) {
            e.preventDefault()
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                beforeSend: function() {
                    $('#pinError').text('').hide();
                    $('#verifyBtn').text('Submiting').attr('disabled', true);
                },
                success: function(response) {
                    if (response.status == 'error') {
                        $('#pinError').text(response.message).show();
                    } else if (response.status == 'success') {
                        window.location.href = response.url;
                    }
                },
                complete: function() {
                    $('#verifyBtn').text('Submit').attr('disabled', false);
                }
            });
        });

        $("#checkPinModal").on('show.bs.modal', function() {

            setTimeout(() => {
                $("#user_pin").focus();
            }, 500);
        });

        $("#checkPinModal").on('hide.bs.modal', function() {
            $('#pinError').text('').hide();
            $('input[name="pin"]').val(null);
        });
    </script>
@endsection
