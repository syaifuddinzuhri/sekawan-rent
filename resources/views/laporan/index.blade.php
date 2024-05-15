@extends('layouts.main')

@section('content')
    <div class="box">
        <div class="d-flex justify-content-between box-header align-items-center flex-wrap">
            <h4 class="box-title fw-600">Laporan</h4>
        </div>
        <div class="box-body">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3 mb-sm-0">
                    <label for="date" class="form-label">Pemesanan Tanggal Dari</label>
                    <input type="text" class="form-control flatpickr" id="from" name="from">
                    <small class="invalid-form text-danger" id="date-error"></small>
                </div>
                <div class="col-md-3 mb-3 mb-sm-0">
                    <label for="date" class="form-label">Pemesanan Tanggal Sampai</label>
                    <input type="text" class="form-control flatpickr" id="to" name="to">
                    <small class="invalid-form text-danger" id="date-error"></small>
                </div>
                <div class="col-md-3 mb-3 mb-sm-0">
                    <button class="btn btn-sm btn-primary" id="btn-report" onclick="handleReport()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div id="box-preview">

    </div>
@endsection

@section('scripts')
    <script>
        function handleReport() {
            var from = $('#from').val();
            var to = $('#to').val();
            const payload = {
                from,
                to
            }
            $.ajax({
                url: "{{ route('laporan.report') }}",
                method: "POST",
                data: payload,
                beforeSend: function() {
                    $('#box-preview').empty();
                    var spinner =
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                    $('#box-preview').html(spinner);
                },
                success: function(response) {
                    $('#box-preview').empty();
                    $('#box-preview').html(response);
                },
                error: function(xhr) {
                    $('#box-preview').empty();
                    errorHandlingAjax(xhr);
                }
            });
        }
    </script>
@endsection
