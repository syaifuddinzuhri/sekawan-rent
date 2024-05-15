<div class="modal fade" id="modal-delete">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi hapus data</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p> Apakah anda yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button> --}}
                <form action="" method="POST" id="form-delete">
                    <button class="btn btn-danger" type="button" onclick="handleDeleteData()"><i
                            class="fa fa-trash"></i> Hapus</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-logout">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi logout</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p> Apakah anda yakin ingin keluar dari sistem?</p>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button> --}}
                <form action="{{ route('logout.submit') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger" type="submit">Logout</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title-form"></h4>
                <button type="button" class="btn-close" onclick="toggleModalForm()"></button>
            </div>
            <div class="modal-body" id="modal-body-form">

            </div>
        </div>
    </div>
</div>

@yield('component')
