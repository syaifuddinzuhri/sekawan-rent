<!-- Page Content overlay -->

<!-- Vendor JS -->
<script src="{{ asset('assets') }}/js/vendors.min.js"></script>
<script src="{{ asset('assets') }}/js/flatpickr.min.js"></script>
<script src="{{ asset('assets') }}/js/pages/chat-popup.js"></script>
<script src="{{ asset('assets') }}/icons/feather-icons/feather.min.js"></script>
<script src="{{ asset('assets') }}/vendor_components/jquery-knob/js/jquery.knob.js"></script>
<script src="{{ asset('assets') }}/vendor_components/raphael/raphael.min.js"></script>
<script src="{{ asset('assets') }}/vendor_components/morris.js/morris.min.js"></script>
<script src="{{ asset('assets') }}/vendor_components/sweetalert/sweetalert.min.js"></script>
<script src="{{ asset('assets') }}/vendor_components/sweetalert/jquery.sweet-alert.custom.js"></script>
<script src="{{ asset('assets') }}/vendor_components/select2/dist/js/select2.full.js"></script>
<script src="{{ asset('assets') }}/vendor_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
<script src="{{ asset('assets') }}/js/template.js"></script>
<script>
    $('.select2').select2();
    flatpickr(".flatpickr", {
        defaultDate: "today",
        dateFormat: "Y-m-d", // Format the date as "YYYY-MM-DD"
    });

    function toggleModalForm(is_open = false) {
        if (is_open) {
            $('#modal-form').modal({
                keyboard: false
            });
            $('#modal-form').modal('show');
        } else {
            $('#modal-form').modal({
                keyboard: false
            });
            $('#modal-form').modal('hide');
            $('#modal-title-form').text('');
            $('#modal-body-form').empty();
            $('#modal-form-action').attr('action', '');
            $('#modal-form-action').attr('data-edit', '');
        }
    }

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Set up the CSRF token in the AJAX headers
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    function showSuccess(message = "OK!") {
        swal({
            type: "success",
            title: "Aksi Berhasil",
            text: message,
            timer: 2000,
        });
    }

    function showError(message = "Maaf, Terdapat kesalahan!") {
        swal({
            type: "error",
            title: "Aksi Gagal",
            text: message,
            timer: 2000,
        });
    }

    function errorHandlingAjax(xhr) {
        var errors = xhr.responseJSON.message;
        if (xhr.status === 422) {
            $('.invalid-form').empty();
            $.each(errors, function(key, value) {
                $('#' + key + '-error').html(value[0]);
            });
        } else {
            showError(errors);
        }
    }

    function setLocalStorage(key, value) {
        localStorage.setItem(key, value);
    }

    function removeLocalStorage(key) {
        localStorage.removeItem(key);
    }
</script>
@yield('scripts')
