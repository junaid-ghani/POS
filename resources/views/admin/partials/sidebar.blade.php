<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}" @if(isset($set) && $set == 'dashboard') class="active" @endif><i data-feather="box"></i><span>Dashboard</span></a></li>
                        {{-- <li><a href="javascript:void(0)" @if(isset($set) && ($set == 'sales' || $set == 'create_sale')) class="active" @else data-bs-toggle="modal" data-bs-target="#checkPinModal"  @endif ><i data-feather="shopping-cart"></i><span>Transactions</span></a></li> --}}
                        <li><a href="{{ route('admin.sales') }}" @if(isset($set) && ($set == 'sales' || $set == 'create_sale')) class="active" @else @endif ><i data-feather="shopping-cart"></i><span>Transactions</span></a></li>
                        <!--<li class="submenu">-->
                        <!--    <a href="javascript:void(0);" class="subdrop @if(isset($set) && ($set == 'sales' || $set == 'create_sale')) active @endif"><i data-feather="shopping-cart"></i><span>Sales</span><span class="menu-arrow"></span></a>-->
                        <!--    <ul>-->
                        <!--        <li><a href="{{ route('admin.search_location') }}" @if(isset($set) && $set == 'create_sale') class="active" @endif>Create Sale</a></li>-->
                        <!--        <li><a href="{{ route('admin.sales') }}" @if(isset($set) && $set == 'sales') class="active" @endif>Manage Sales</a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->


                        {{-- <li class="submenu">
                            <a href="javascript:void(0);"  class="subdrop @if(isset($set) && ($set == 'Salaries')) active @endif"><i data-feather="file-text"></i><span>Reports</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="#">Profit and loss</a></li>
                                <li><a href="{{ route('admin.salary') }}" @if(isset($set) && $set == 'Salaries') class="active" @endif>Salaries</a></li>
                                <li><a href="#">Payment Methods</a></li>
                            </ul>
                        </li> --}}

                        <li><a href="{{ route('admin.salary') }}" @if(isset($set) && $set == 'Salaries') class="active" @endif><i data-feather="box"></i><span>Reports</span></a></li>

                        {{-- <li class="submenu">
                            <a href="javascript:void(0);" class="subdrop @if(isset($set) && ($set == 'assign_products' || $set == 'transfer_products' || $set == 'stocks')) active @endif"><i data-feather="package"></i><span>Inventory</span><span class="menu-arrow"></span></a>
                            <ul>
                                
                                <li><a href="{{ route('admin.stocks') }}" @if(isset($set) && $set == 'stocks') class="active" @endif>Manage Inventory</a></li>
                                <li><a href="{{ route('admin.transfer_products') }}" @if(isset($set) && $set == 'transfer_products') class="active" @endif>Transfers</a></li>
                                <li><a href="{{ route('admin.assign_products') }}" @if(isset($set) && $set == 'assign_products') class="active" @endif>Assign Products</a></li>
                            </ul>
                        </li> --}}

                        <li><a href="{{ route('admin.inventory') }}" @if(isset($set) && $set == 'inventory') class="active" @endif><i data-feather="box"></i><span>Inventory</span></a></li>
                        <li><a href="{{ route('admin.items') }}" @if(isset($set) && $set == 'items') class="active" @endif><i data-feather="box"></i><span>Items</span></a></li>

                        {{-- <li><a href="{{ route('admin.products') }}" @if(isset($set) && $set == 'products') class="active" @endif><i data-feather="box"></i><span>Products</span></a></li> --}}
                        {{-- <li><a href="{{ route('admin.category') }}" @if(isset($set) && $set == 'category') class="active" @endif><i data-feather="codepen"></i><span>Categories</span></a></li> --}}

                        <li><a href="{{ route('admin.users') }}" @if(isset($set) && $set == 'users') class="active" @endif><i data-feather="users"></i><span>Users</span></a></li>
                        <li><a href="{{ route('admin.customers') }}" @if(isset($set) && $set == 'customers') class="active" @endif><i data-feather="user"></i><span>Customers</span></a></li>
                        <li><a href="{{ route('admin.locations') }}" @if(isset($set) && $set == 'locations') class="active" @endif><i data-feather="home"></i><span>Locations</span></a></li>
                        <li><a  href="{{ route('admin.setting') }}" @if(isset($set) && $set == 'setting') class="active" @endif><i data-feather="settings"></i><span>Settings</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

@section('modal_contents')
    <div class="modal fade" id="checkPinModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Verify PIN</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="alert alert-danger" style="display:none" id="pinError"></div>
            <form action="{{ route('admin.verify_pin') }}" method="POST" id="verify_pin">
                @csrf
                <div class="form-group">
                    <label>Enter PIN *</label>
                    <input type="number" name="pin" class="form-control" required>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary" id="verifyBtn">Verify</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('custom_script2')

    <script>
        $(document).ready(function () {
            
            let settingRoute = "{{ route('admin.setting') }}"
          
            let currentRoute = window.location.href;
          
            if (currentRoute != settingRoute) {
                localStorage.removeItem('currentTab')
            }
        })
        
    </script>
@endsection