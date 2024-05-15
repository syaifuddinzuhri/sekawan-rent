@extends('layouts.main')

@section('content')
    <div class="mb-4">
        <button class="btn btn-sm btn-primary" onclick="addKendaraan()">Tambah Kendaraan</button>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="d-flex justify-content-between box-header align-items-center flex-wrap">
                    <h4 class="box-title fw-600">Data Master Kendaraan</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table-kendaraan" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- <th>Gambar</th> --}}
                                    <th>Nama</th>
                                    <th>Merk</th>
                                    <th>Nomor Polisi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('assets') }}/vendor_components/datatable/datatables.min.js"></script>
    <script>
        let table = $("#table-kendaraan");

        var datatable = table.DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('kendaraan.index') }}",
            },
            columns: [{
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    className: "text-center",
                    width: "4%",
                },
                // {
                //     data: "image",
                //     name: "image",
                // },
                {
                    data: "name",
                    name: "name",
                },
                {
                    data: "brand",
                    name: "brand",
                },
                {
                    data: "police_number",
                    name: "police_number",
                },
                {
                    data: "status",
                    name: "status",
                },
                {
                    data: "action",
                    name: "action",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                },
            ],
        });


        table.on("click", ".delete", function(e) {
            var delete_url = $(this).closest("tr").attr("url");
            var form_delete = $('#form-delete');
            form_delete.attr('action', delete_url);
        });

        table.on("click", ".edit", function(e) {
            var edit_url = $(this).closest("tr").attr("edit_url");
            $.ajax({
                url: edit_url,
                method: "GET",
                success: function(response) {
                    toggleModalForm(true);
                    $('#modal-title-form').text('Form Edit Kendaraan');
                    $('#modal-body-form').html(response);
                },
                error: function(xhr) {
                    showError();
                }
            });
        });

        function addKendaraan() {
            $.ajax({
                url: "{{ route('kendaraan.create') }}",
                method: "GET",
                success: function(response) {
                    toggleModalForm(true);
                    $('#modal-title-form').text('Form Tambah Kendaraan');
                    $('#modal-body-form').html(response);
                },
                error: function(xhr) {
                    showError();
                }
            });
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function onSubmitForm() {

            var form = $('#modal-form-action');
            var actionUrl = form.attr('action');
            var isEdit = form.attr('data-edit');
            $.ajax({
                url: actionUrl,
                method: isEdit == 1 ? 'PUT' : 'POST',
                data: {
                    name: $('#name').val(),
                    brand: $('#brand').val(),
                    status: $('#status').is(':checked') ? 'available' : 'not-available',
                    police_number: $('#police_number').val(),
                },
                success: function(response) {
                    if (response.status) {
                        showSuccess(response.message);
                        toggleModalForm();
                        datatable.draw();
                    }
                },
                error: function(xhr) {
                    errorHandlingAjax(xhr);
                }
            });
        }


        function handleDeleteData() {
            var form = $('#form-delete');
            var actionUrl = form.attr('action');
            $.ajax({
                url: actionUrl,
                method: 'DELETE',
                success: function(response) {
                    if (response.status) {
                        showSuccess(response.message);
                        toggleModalForm();
                        datatable.draw();
                        $('#modal-delete').modal({
                            keyboard: false
                        });
                        $('#modal-delete').modal('hide');
                    }
                },
                error: function(xhr) {
                    errorHandlingAjax(xhr);
                }
            });
        }

        updateStatusLabel();

        function changeStatus() {
            updateStatusLabel();
        };

        function updateStatusLabel() {
            var statusLabel = $('#statusLabel');
            if ($('#status').is(':checked')) {
                statusLabel.text('Tersedia');
            } else {
                statusLabel.text('Tidak Tersedia');
            }
        }
    </script>
@endsection
