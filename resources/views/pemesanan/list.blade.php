<div class="row">
    @foreach ($data as $item)
        <div class="col-sm-6 col-md-6 col-lg-3" style="cursor: pointer;"
            onclick="handleSelectKendaraan('{{ $item->id }}')">
            <div class="box ribbon-box">
                <div class="ribbon-two ribbon-two-primary" style="display: none;" id="ribbon-{{ $item->id }}">
                    <span>Dipilih</span>
                </div>
                <div class="box-header no-border p-0">
                    <img style="width: 100%; object-fit: contain;" src="{{ asset('assets/images/mobil.jpg') }}"
                        alt="" height="200">
                </div>
                <div class="box-body border">
                    <div class="text-center">
                        <h3><a href="#">{{ $item->police_number }}</a></h3>
                        <h6 class="user-info mt-0 mb-10 text-fade">{{ $item->name }} | {{ $item->brand }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
