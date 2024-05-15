@extends('layouts.main')
@section('css')
    <style>
        .draggable-list {
            border: 1px solid var(--border-color);
            color: var(--text-color);
            padding: 0;
            list-style-type: none;
        }

        .draggable-list li {
            background-color: #fff;
            display: flex;
            flex: 1;
        }

        .draggable-list li:not(:last-of-type) {
            border-bottom: 1px solid #F3F6F9;
        }

        .draggable-list .number {
            background-color: #F3F6F9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            width: 40px;
        }

        .draggable-list li.over .draggable {
            background-color: #eaeaea;
        }

        .draggable-list .item-name {
            margin: 0 20px 0 0;
        }

        .draggable {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            flex: 1;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="search" class="form-label">Pencarian</label>
                <input type="text" class="form-control" id="search"
                    placeholder="Masukkan nama kendaraan atau nomor polisi" onkeyup="getListKendaraan()">
            </div>
            <label for="" class="form-label">Pilih Kendaraan</label>
            <div id="list-kendaraan"></div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="d-flex justify-content-between box-header align-items-center flex-wrap">
                    <h4 class="box-title fw-600">Form Pemesanan</h4>

                </div>
                <div class="box-body">
                    <form action="{{ route('pemesanan.store') }}" id="form-pemesanan">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kendaraan</label>
                            <input type="text" class="form-control" id="name"
                                placeholder="Pilih kendaraan terlebih dahulu" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="text" class="form-control flatpickr" placeholder="Masukkan tanggal"
                                id="date" name="date">
                            <small class="invalid-form text-danger" id="date-error"></small>
                        </div>
                        <div class="mb-3">
                            <label for="driver" class="form-label">Driver</label>
                            <select class="form-select select2" name="driver" id="driver"
                                aria-label="Default select example" style="width: 100%">
                                <option selected disabled>Pilih driver</option>
                                @foreach ($driver as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                            <small class="invalid-form text-danger" id="user_driver_id-error"></small>
                        </div>
                        <div class="mb-3">
                            <label for="approver" class="form-label">Pihak Menyetujui</label>
                            <select class="form-select" name="approver" id="approver" aria-label="Default select example"
                                multiple onchange="onChangeApprover()" style="width: 100%">
                                <option disabled>Pilih Pihak Menyetujui</option>
                                @foreach ($approver as $item)
                                    <option value="{{ $item->id }}" data-name="{{ $item->name }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                            <small class="invalid-form text-danger" id="approver-error"></small>
                        </div>
                        <label for="" id="label-approver" style="display: none;">Urutan Pihak Menyetujui</label>
                        <ul class="draggable-list" id="draggable-list"></ul>
                        <button type="button" class="btn btn-sm btn-primary" onclick="handleSubmit()">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $('#approver').select2({
            multiple: true,
        });

        getListKendaraan();

        function getListKendaraan() {
            removeLocalStorage('kendaraan');
            $.ajax({
                url: "{{ route('kendaraan.list') }}",
                method: "GET",
                data: {
                    search: $('#search').val(),
                },
                success: function(response) {
                    $('#list-kendaraan').html(response);
                },
                error: function(xhr) {
                    errorHandlingAjax(xhr);
                }
            });
        }

        function handleSelectKendaraan(id) {
            $('.ribbon-two').hide();
            $(`#ribbon-${id}`).show();
            setLocalStorage('kendaraan', id);
            var routeUrl = "{{ route('kendaraan.show', ['kendaraan' => ':id']) }}";
            var replacedUrl = routeUrl.replace(':id', id);
            $.ajax({
                url: routeUrl.replace(':id', id),
                method: "GET",
                success: function(response) {
                    if (response.status) {
                        var text =
                            `${response.data.police_number} - ${response.data.name} | ${response.data.brand}`
                        $('#name').attr('placeholder', text)
                    }
                },
                error: function(xhr) {
                    errorHandlingAjax(xhr);
                }
            });
        }

        const draggable_list = document.getElementById('draggable-list');

        let listApprovers = [];

        let listItems = [];

        let dragStartIndex;

        document.addEventListener('DOMContentLoaded', () => {
            createRandomizedList();
            addEventListeners();
        });

        function onChangeApprover() {
            var selectedIds = $('#approver').val() || [];
            var selectedNames = [];
            if (selectedIds.length > 0) {
                selectedIds.forEach(function(id) {
                    var option = $('#approver').find('option[value="' + id + '"]');
                    if (option.length > 0) {
                        selectedNames.push(option.attr('data-name'));
                    }
                });

                var mergedArray = selectedIds.map(function(id, index) {
                    return {
                        id: id,
                        name: selectedNames[index] || '',
                        index: index
                    };
                });
                listApprovers = mergedArray;
                createRandomizedList();
                addEventListeners();
                $('#label-approver').show();
            } else {
                $('#label-approver').hide();
                draggable_list.innerHTML = '';
            }
        }

        function createRandomizedList() {
            draggable_list.innerHTML = '';
            listItems = [];
            if (listApprovers.length > 0) {
                listApprovers.forEach(({
                    name,
                    index,
                    id
                }) => {
                    const listItem = document.createElement('li');
                    listItem.setAttribute('data-index', index);
                    listItem.innerHTML = `
                <span class="number">${index + 1}</span>
                <div class="draggable" draggable="true">
                    <p class="item-name" data-id="${id}">${name}</p>
                    <i class="fa fa-sort"></i>
                </div>
            `;
                    listItems.push(listItem);
                    draggable_list.appendChild(listItem);
                });
            }
        }

        function dragStart() {
            dragStartIndex = +this.closest('li').getAttribute('data-index');
        }

        function dragEnter() {
            this.classList.add('over');
        }

        function dragLeave() {
            this.classList.remove('over');
        }

        function dragOver(e) {
            e.preventDefault();
        }

        function dragDrop() {
            const dragEndIndex = +this.getAttribute('data-index');
            swapItems(dragStartIndex, dragEndIndex);
            this.classList.remove('over');
        }

        function swapItems(fromIndex, toIndex) {
            const itemOne = listItems[fromIndex].querySelector('.draggable');
            const itemTwo = listItems[toIndex].querySelector('.draggable');
            listItems[fromIndex].appendChild(itemTwo);
            listItems[toIndex].appendChild(itemOne);
        }

        function addEventListeners() {
            const draggables = document.querySelectorAll('.draggable');
            const dragListItems = document.querySelectorAll('.draggable-list li');

            draggables.forEach(draggable => {
                draggable.addEventListener('dragstart', dragStart);
            });

            dragListItems.forEach(item => {
                item.addEventListener('dragover', dragOver);
                item.addEventListener('drop', dragDrop);
                item.addEventListener('dragenter', dragEnter);
                item.addEventListener('dragleave', dragLeave);
            });
        }

        function getApproverIds() {
            const idArray = [];
            $('#draggable-list li').each(function() {
                const dataId = $(this).find('.item-name').attr('data-id');
                idArray.push(dataId);
            });
            return idArray;
        }

        function handleSubmit() {
            var vehicleId = localStorage.getItem('kendaraan');
            if (vehicleId) {
                var payload = {
                    vehicle_id: vehicleId,
                    date: $('#date').val(),
                    user_driver_id: $('#driver').val(),
                    user_approver_ids: getApproverIds()
                }
                var form = $('#form-pemesanan');
                var actionUrl = form.attr('action');

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: payload,
                    success: function(response) {
                        if (response.status) {
                            showSuccess(response.message);
                        }
                        window.location = '/pemesanan';
                    },
                    error: function(xhr) {
                        errorHandlingAjax(xhr);
                    }
                });
            } else {
                showError('Pilih kendaraan terlebih dahulu');
            }
        }
    </script>
@endsection
