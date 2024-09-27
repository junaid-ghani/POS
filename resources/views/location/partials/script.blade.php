<script src="{{ asset(config('constants.admin_path').'js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/feather.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/apexchart/chart-data.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/moment.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/jquery.validate.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/location_form_validations.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/script.js') }}"></script>
<script>

@if(Session::has('success'))
Swal.fire({
    title: "Good job!",
    text: "{{ Session::get('success') }}",
    type: "success",
    confirmButtonClass: "btn btn-primary",
    buttonsStyling: !1,
    icon: "success"
});
@endif
@if(Session::has('error'))
Swal.fire({
    title: "Warning!",
    text: "{{ Session::get('error') }}",
    type: "warning",
    confirmButtonClass: "btn btn-primary",
    buttonsStyling: !1,
    icon: "warning"
});
@endif

    $('.phone_format_validate').on('input', function(e) {
        var input = $(this).val();
        var cleaned = input.replace(/\D/g, ''); 
    
        if (cleaned.length === 0) {
            $(this).val(''); 
        } else if (cleaned.length <= 3) {
            $(this).val('(' + cleaned); 
        } else if (cleaned.length <= 6) {
            $(this).val('(' + cleaned.slice(0, 3) + ') ' + cleaned.slice(3)); 
        } else if (cleaned.length <= 10) {
            $(this).val('(' + cleaned.slice(0, 3) + ') ' + cleaned.slice(3, 6) + '-' + cleaned.slice(6, 10)); 
        } else {
            $(this).val('(' + cleaned.slice(0, 3) + ') ' + cleaned.slice(3, 6) + '-' + cleaned.slice(6, 10)); 
        }
    });

</script>