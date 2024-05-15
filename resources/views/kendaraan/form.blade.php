<form action="{{ $route }}" id="modal-form-action" data-edit="{{ isset($data) ? 1 : 0 }}">
    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control" id="name" placeholder="Masukkan nama"
            value="{{ isset($data) ? $data->name : '' }}">
        <small class="invalid-form text-danger" id="name-error"></small>
    </div>
    <div class="mb-3">
        <label for="brand" class="form-label">Merk</label>
        <input type="text" class="form-control" id="brand" placeholder="Masukkan Merk"
            value="{{ isset($data) ? $data->brand : '' }}">
        <small class="invalid-form text-danger" id="brand-error"></small>
    </div>
    <div class="mb-3">
        <label for="police_number" class="form-label">Nomor Polisi</label>
        <input type="text" class="form-control" id="police_number" placeholder="Masukkan Nomor Polisi"
            value="{{ isset($data) ? $data->police_number : '' }}">
        <small class="invalid-form text-danger" id="police_number-error"></small>
    </div>
    <div class="mb-3">
        <div class="form-check p-0 form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="status" onchange="changeStatus()"
                {{ isset($data) && $data->status === 'available' ? 'checked' : '' }}>
            <label class="form-check-label" id="statusLabel"
                for="status">{{ isset($data) && $data->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}</label>
        </div>
    </div>
    {{-- <div class="mb-3">
        <label for="image" class="form-label">Gambar</label>
        <input class="form-control" type="file" id="image" onchange="previewImage(this)">
        <small class="invalid-form text-danger" id="image-error"></small>
        <img src="{{ asset('assets/images/mobil.jpg') }}" id="image-preview" class="img-thumbnail mt-2"
            alt="gambar kendaraan" width="200">
    </div> --}}
    <button type="button" class="btn btn-sm btn-primary" id="btn-submit-form" onclick="onSubmitForm()">Simpan</button>
</form>
