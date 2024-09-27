<div class="table-list-card">
    <div class="table-top">
        {{-- <div class="search-set">
            <div class="search-input">
                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                    class="feather-search"></i></a>
                </div>
            </div> --}}
        </div>
    <small id="transfer_error_2" style="color: red;"></small>
    <small id="transfer_error" style="color: red;"></small>
    <div class="table-responsive">
        <table id="product_table" class="table" style="width: 100%">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Barcode</th>
                    <th>Item Name</th>
                    <th>Stock</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <input type="checkbox" class="transfer_checkbox" name="transfer_products[{{ $product->product_id }}]"
                                value="{{ $product->product_id }}">
                        </td>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>
                            <input type="number" class="form-control"
                                name="transfer_quantity[{{ $product->product_id }}]" autocomplete="off">
                            <div class="invalid-feedback">Product’s quantity must be greater than 0</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    $('#transfer_products_form').on('submit', function(e) {
        var isValid = true;
        var productChosen = false;

        $("#transfer_error").text('');
        $("#transfer_error_2").text('');

        $('.transfer_checkbox:checked').each(function() {
            productChosen = true;
            var productId = $(this).val();
            var quantityInput = $('input[name="transfer_quantity[' + productId + ']"]');
            var quantityValue = parseInt(quantityInput.val());

            if (!quantityValue || !Number.isInteger(quantityValue)) {
                // quantityInput.addClass('is-invalid');
                // quantityInput.siblings('.qty_error').css('display', 'block'); // Show quantity error
                isValid = false;
            } else if (quantityValue <= 0) {
                $("#transfer_error_2").text('');
                quantityInput.addClass('is-invalid');
                quantityInput.siblings('.qty_error').css('display', 'block')
                isValid = false;
            } else {
                quantityInput.removeClass('is-invalid');
                quantityInput.siblings('.qty_error').css('display', 'none');
            }
        });

        if (!productChosen) {
            $("#transfer_error").text("Please select a product");
            e.preventDefault();
            return;
        }

        if (!isValid) {
            e.preventDefault();
            $("#transfer_error_2").text("Please choose a Valid quantity for the selected product(s)");
        }
    });

    $('.transfer_checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            $("#transfer_error").text('');
        }
    });

    $('input[name^="transfer_quantity"]').on('input', function() {

        var quantityValue = parseInt($(this).val());
        if (quantityValue > 0) {
            $("#transfer_error_2").text('');
            $(this).removeClass('is-invalid');
            $(this).siblings('.qty_error').css('display', 'none');
        }
    });


    $('#product_table').DataTable({
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
        // initComplete: (settings, json) => {
        //     $('.dataTables_filter').appendTo('#tableSearch');
        //     $('.dataTables_filter').appendTo('.search-input');
        // },
        // "iDisplayLength": 50,
    });
</script>
