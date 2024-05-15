@extends('layouts.main')

@section('content')
    <a href="{{ route('pemesanan.index') }}" class="btn btn-sm btn-secondary mb-3">Kembali</a>
    <div class="row">
        <div class="col-6">
            <div class="box">
                <div class="d-flex justify-content-between box-header align-items-center flex-wrap">
                    <h4 class="box-title fw-600">Detail Pesanan</h4>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 my-2">Tanggal</div>
                        <div class="col-md-6 my-2 fw-bold">{{ $data->date }}</div>
                        <div class="col-md-6 my-2">Kendaraan</div>
                        <div class="col-md-6 my-2 fw-bold">
                            {{ $data->vehicle ? $data->vehicle->police_number . ' - ' . $data->vehicle->name . ' | ' . $data->vehicle->brand : '' }}
                        </div>
                        <div class="col-md-6 my-2">Driver</div>
                        <div class="col-md-6 my-2 fw-bold">{{ $data->driver->name ?? '' }}</div>
                        <div class="col-md-6 my-2">Status</div>
                        <div class="col-md-6 my-2 fw-bold">
                            {!! statusBadge($data->status) !!}

                        </div>
                        @if (in_array($data->status, ['pending']))
                            <div class="col-md-6 my-2">
                                <form method="POST" action="{{ route('pemesanan.update', $data->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-sm btn-primary ms-2">Tutup Pesanan</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <hr>
                            <p>Pihak Menyetujui</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $previousStatus = null;
                                            $countApproved = 0;
                                        @endphp
                                        @foreach ($data->approvals as $item)
                                            @php
                                                $currentStatus = $item->status;
                                            @endphp
                                            <form method="POST" action="{{ route('update.approval', $item->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $item->approver->name ?? '' }}</td>
                                                    <td>
                                                        <select class="form-select select2" name="status"
                                                            aria-label="Default select example" style="width: 100%"
                                                            {{ $previousStatus !== 'pending' && $previousStatus !== 'rejected' && $data->status === 'pending' ? '' : 'disabled' }}>
                                                            <option selected disabled>Pilih status</option>
                                                            @foreach (statusOptions() as $option)
                                                                <option value="{{ $option['value'] }}"
                                                                    {{ $item->status == $option['value'] ? 'selected' : '' }}>
                                                                    {{ $option['label'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" id="note"
                                                            name="note" placeholder="Keterangan"
                                                            value="{{ $item->note ?? '' }}"
                                                            {{ $previousStatus !== 'pending' && $previousStatus !== 'rejected' && $data->status === 'pending' ? '' : 'disabled' }}>
                                                    </td>
                                                    <td>
                                                        @if ($previousStatus !== 'pending' && $previousStatus !== 'rejected' && $data->status === 'pending')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary ms-2">Update</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </form>
                                            @php
                                                if ($currentStatus === 'approved') {
                                                    $countApproved++;
                                                }
                                                $previousStatus = $currentStatus;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
