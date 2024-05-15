<div class="box">
    <div class="d-flex justify-content-between box-header align-items-center flex-wrap">
        <h4 class="box-title fw-600">Preview Hasil</h4>
        <form action="{{ route('laporan.export') }}" method="POST">
            @csrf
            <input type="hidden" name="from" value="{{ $from }}">
            <input type="hidden" name="to" value="{{ $to }}">
            <button type="submit" class="btn btn-sm btn-success">Export Excel</button>
        </form>
    </div>
    <div class="box-body">
        <div class="text-center fw-bold">
            <p>Laporan Pemesanan</p>
            <p>{{ $from }} s/d {{ $to }}</p>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th scope="col" rowspan="2">Tanggal</th>
                        <th scope="col" rowspan="2">Kendaraan</th>
                        <th scope="col" rowspan="2">Driver</th>
                        <th scope="col" colspan="3">Approver</th>
                        <th scope="col" rowspan="2">Status</th>
                    </tr>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Status</th>
                        <th scope="col">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td rowspan="{{ count($item->approvals) }}" class="text-center">{{ $item->date }}</td>
                            <td rowspan="{{ count($item->approvals) }}">
                                {{ $item->vehicle ? $item->vehicle->police_number . ' - ' . $item->vehicle->name . ' | ' . $item->vehicle->brand : '' }}
                            </td>
                            <td rowspan="{{ count($item->approvals) }}">{{ $item->driver->name ?? '' }}</td>
                            @if (count($item->approvals) > 0)
                                <td>{{ $item->approvals[0]->approver->name ?? '' }}</td>
                                <td class="text-center">{{ statusValue($item->approvals[0]->status) }}</td>
                                <td>{{ $item->approvals[0]->note ?? '-' }}</td>
                            @endif
                            <td rowspan="{{ count($item->approvals) }}" class="text-center">
                                {{ statusValue($item->status) }}</td>
                        </tr>
                        @foreach ($item->approvals as $index => $detail)
                            @if ($index > 0)
                                <tr>
                                    <td>{{ $detail->approver->name ?? '' }}</td>
                                    <td class="text-center">{{ statusValue($detail->status) }}</td>
                                    <td>{{ $detail->note ?? '-' }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
