<form action="{{ $route }}" id="modal-form-action" data-edit="{{ isset($data) ? 1 : 0 }}">
    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control" id="name" placeholder="Masukkan nama"
            value="{{ isset($data) ? $data->name : '' }}">
        <small class="invalid-form text-danger" id="name-error"></small>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" placeholder="Masukkan email"
            value="{{ isset($data) ? $data->email : '' }}">
        <small class="invalid-form text-danger" id="email-error"></small>
    </div>
    <div class="mb-3">
        <label for="phome" class="form-label">Nomor HP</label>
        <input type="number" class="form-control" id="phone" placeholder="Masukkan nomor HP"
            value="{{ isset($data) ? $data->phone : '' }}">
        <small class="invalid-form text-danger" id="phone-error"></small>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select select2" name="role" id="role" aria-label="Default select example">
            <option selected disabled>Pilih role</option>
            @foreach (roleOptions() as $item)
                <option value="{{ $item['value'] }}"
                    {{ isset($data) && $data->role == $item['value'] ? 'selected' : '' }}>{{ $item['label'] }}</option>
            @endforeach
        </select>
        <small class="invalid-form text-danger" id="role-error"></small>
    </div>
    @if (!isset($data))
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Masukkan Password">
            <small class="invalid-form text-danger" id="password-error"></small>
        </div>
    @endif
    <div class="mb-3">
        <label for="address" class="form-label">Alamat</label>
        <textarea class="form-control" id="address" rows="3">{{ isset($data) ? $data->address : '' }}</textarea>
        <small class="invalid-form text-danger" id="address-error"></small>
    </div>
    <button type="button" class="btn btn-sm btn-primary" id="btn-submit-form" onclick="onSubmitForm()">Simpan</button>
</form>
