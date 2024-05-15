@extends('layouts.main')

@section('content')
    <div class="mb-4">
        <button class="btn btn-sm btn-primary" onclick="addUser()">Tambah User</button>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="d-flex justify-content-between box-header align-items-center flex-wrap">
                    <h4 class="box-title fw-600">Data Master User</h4>
                    <div class="btn-group mt-4 mt-sm-0" role="group" aria-label="Basic mixed styles example">
                        <button type="button" class="btn btn-sm btn-primary" id="semua"
                            onclick="onClickFilter('')">Semua</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="admin"
                            onclick="onClickFilter('admin')">Admin</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="approver"
                            onclick="onClickFilter('approver')">Approver</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="driver"
                            onclick="onClickFilter('driver')">Driver</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table-user" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>No. HP</th>
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
        let table = $("#table-user");
        let roleFilter = '';

        var datatable = table.DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('user.index') }}",
                data: function(data) {
                    data.role = roleFilter;
                }
            },
            columns: [{
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    className: "text-center",
                    width: "4%",
                },
                {
                    data: "name",
                    name: "name",
                },
                {
                    data: "email",
                    name: "email",
                },
                {
                    data: "role",
                    name: "role",
                },
                {
                    data: "phone",
                    name: "phone",
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

        function onClickFilter(role) {
            roleFilter = role;
            var btnSemua = $('#semua');
            var btnAdmin = $('#admin');
            var btnApprover = $('#approver');
            var btnDriver = $('#driver');
            switch (role) {
                case 'admin':
                    btnSemua.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnApprover.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnDriver.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnAdmin.removeClass('btn-outline-primary').addClass('btn-primary');
                    break;
                case 'approver':
                    btnSemua.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnAdmin.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnDriver.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnApprover.removeClass('btn-outline-primary').addClass('btn-primary');
                    break;
                case 'driver':
                    btnSemua.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnAdmin.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnApprover.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnDriver.removeClass('btn-outline-primary').addClass('btn-primary');
                    break;

                default:
                    btnDriver.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnAdmin.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnApprover.removeClass('btn-primary').addClass('btn-outline-primary');
                    btnSemua.removeClass('btn-outline-primary').addClass('btn-primary');
                    break;
            }
            datatable.draw();
        }

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
                    $('#modal-title-form').text('Form Edit User');
                    $('#modal-body-form').html(response);
                },
                error: function(xhr) {
                    errorHandlingAjax(xhr);
                }
            });
        });

        function addUser() {
            $.ajax({
                url: "{{ route('user.create') }}",
                method: "GET",
                success: function(response) {
                    toggleModalForm(true);
                    $('#modal-title-form').text('Form Tambah User');
                    $('#modal-body-form').html(response);
                },
                error: function(xhr) {
                    errorHandlingAjax(xhr);
                }
            });
        }

        function onSubmitForm() {

            var form = $('#modal-form-action');
            var actionUrl = form.attr('action');
            var isEdit = form.attr('data-edit');
            $.ajax({
                url: actionUrl,
                method: isEdit == 1 ? 'PUT' : 'POST',
                data: {
                    email: $('#email').val(),
                    password: $('#password').val(),
                    name: $('#name').val(),
                    phone: $('#phone').val(),
                    address: $('#address').val(),
                    role: $('#role').val(),
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
    </script>
@endsection
