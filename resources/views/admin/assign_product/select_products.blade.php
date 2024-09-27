
<div class="table-list-card">
    <div class="table-top">
        {{-- <div class="search-set">
            <div class="search-input">
                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
            </div>
        </div> --}}
    </div>
    <div class="table-responsive">
        <button class="btn btn-submit" type="button" id="select-all-btn">Select All</button>
        <button class="btn btn-submit" type="button" id="deselect-all-btn">Deselect  All</button>
        <table id="assign_product_table" class="table" style="width: 100%">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Item Name</th>
                    {{-- <th>Code</th>
                    <th>Stock</th>
                    <th>Quantity</th> --}}
                </tr>
            </thead>
            <tbody>
                
                @foreach($products as $product)
                <tr>
                    <td>
                        {{-- <input type="checkbox" class="product-checkbox" name="transfer_products[{{ $product->product_id }}]" value="{{ $product->product_id }}"> --}}
                        <input type="checkbox" class="product-checkbox" name="stock_product[]" value="{{ $product->product_id }}">
                    </td>
                    <td>{{ $product->product_name }}</td>
                    {{-- <td>{{ $product->product_code }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        <input type="number" class="form-control" name="transfer_quantity[{{ $product->product_id }}]" autocomplete="off">
                    </td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
     $(document).ready(function() {
        
        $('#deselect-all-btn').hide();

        $('#select-all-btn').click(function() {
            $('.product-checkbox').prop('checked', true);
            $('#select-all-btn').hide();
            $('#deselect-all-btn').show();
        });

        $('#deselect-all-btn').click(function() {
            $('.product-checkbox').prop('checked', false);
            $('#select-all-btn').show();
            $('#deselect-all-btn').hide();
        });

    });
$('#assign_product_table').DataTable({
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
    // initComplete: (settings, json)=>{
    //     $('.dataTables_filter').appendTo('#tableSearch');
    //     $('.dataTables_filter').appendTo('.search-input');
    // },
    // "iDisplayLength": 50,
});
</script>