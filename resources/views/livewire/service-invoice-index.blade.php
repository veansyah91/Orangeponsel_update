<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header h4">
                        Nota Service
                    </div>
                    <div class="card-body">
                        <div class="form-group" style="display:block">
                            <label for="tipe_handphone">
                                <strong>Nomor</strong> 
                            </label>
                            <div class="row">
                                <div class="col-lg-3 text-center text-lg-left mb-1 mb-lg-0">
                                    <button class="btn btn-secondary btn-block" type="button" wire:click="showSearchFunc()">Cari</button>
                                </div>
                                <div class="col-lg-9" style="display: block">
                                    <div class="w-100">
                                        <input type="text" class="form-control @error('nomor') is-invalid @enderror" id="nomor" wire:model="nomor" readonly>
                                    </div>
                                    <div class="list-group {{ $showSearch ? '' : 'd-none' }}" style="position: absolute; z-index: 1">
                                        <input type="text" class="form-control" placeholder="Masukkan No HP/Nama" wire:model="searchName">
                                        @foreach ($services as $service)
                                            <button type="button" class="list-group-item list-group-item-action" wire:click="selectservice({{ $service->id }},'{{ $service->nama }}','{{ $service->no_hp }}')">
                                                {{ $service->nama }} / {{ $service->tipe }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>                       
                        </div>

                        <div class="form-group" style="display:block">
                            <label for="tipe_handphone">
                                <strong>Nomor</strong> 
                            </label>
                            <div class="row">
                                <div class="col-lg-3 text-center text-lg-left mb-1 mb-lg-0">
                                    <button class="btn btn-secondary btn-block" type="button" wire:click="showNameSearch()">Cari</button>
                                </div>
                                <div class="col-lg-9" style="display: block">
                                    <div class="w-100">
                                        <input type="text" class="form-control @error('creditCustomerName') is-invalid @enderror" id="tipe_handphone" wire:model="creditCustomerName" readonly>
                                    </div>
                                </div>
                            </div>                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
