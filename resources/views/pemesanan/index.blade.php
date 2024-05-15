@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="d-flex justify-content-between box-header align-items-center flex-wrap">
                    <h4 class="box-title fw-600">Data Pemesanan</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table-pemesanan" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Kendaraan</th>
                                    <th>Driver</th>
                                    <th>Approver</th>
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
        let table = $("#table-pemesanan");
        let statusFilter = '';

        var datatable = table.DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('pemesanan.index') }}",
                // data: function(data) {
                // data.role = statusFilter;
                // }
            },
            columns: [{
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    className: "text-center",
                    width: "4%",
                },
                {
                    data: "date",
                    name: "date",
                },
                {
                    data: "name",
                    name: "name",
                },
                {
                    data: "driver",
                    name: "driver",
                },
                {
                    data: "approver",
                    name: "approver",
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
    </script>
@endsection
