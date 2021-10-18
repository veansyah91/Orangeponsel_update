<div>
    <div class="form-input">
        <div class="row">
            <div class="col-12 col-lg-8">
                <form wire:submit.prevent="update()">
                    <div class="form-group" style="display:block">
                        <label for="tipe_handphone">
                            <strong>Kode / Imei</strong> 
                        </label>
                        <div class="row">
                            <div class="col-lg-3 text-center text-lg-left mb-1 mb-lg-0">
                                <button class="btn btn-secondary btn-block" type="button" wire:click="showSearchFunc()">Cari</button>
                            </div>
                            <div class="col-lg-9" style="display: block">
                                <div class="w-100">
                                    <input type="text" class="form-control @error('kode') is-invalid @enderror"  wire:model="kode" readonly>
                                </div>
                                <div class="list-group {{ $showSearch ? '' : 'd-none' }}" style="position: absolute; z-index: 1">
                                    <input type="text" class="form-control" placeholder="Masukkan Kode/Imei" wire:model="productSearch">
                                    @foreach ($products as $product)
                                        <button type="button" class="list-group-item list-group-item-action" wire:click="selectProduct({{ $product->id }})">
                                            {{ $product->kode}} / {{ $product->tipe }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>                       
                    </div>
                    <div class="form-group row">
                        <label for="jumlah" class="col-sm-3 col-form-label">
                            <strong>Tipe</strong> 
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" wire:model="productName" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jumlah" class="col-sm-3 col-form-label">
                            <strong>Jumlah</strong> 
                        </label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" wire:model="jumlah">
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit">Ubah</button>
                    <button class="btn btn-secondary" type="button" wire:click="showIndex">Batal</button>
                </form>
            </div>
        </div>
        
    </div>
</div>
