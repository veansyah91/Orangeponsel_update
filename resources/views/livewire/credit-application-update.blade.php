<div>
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-6 col-10">
                <form wire:submit.prevent="update()">
                    <div class="form-group" style="display:block">
                        <label for="tipe_handphone">
                            <strong>Nama Konsumen</strong> 
                        </label>
                        <div class="row">
                            <div class="col-lg-3 text-center text-lg-left mb-1 mb-lg-0">
                                <button class="btn btn-secondary btn-block" type="button" wire:click="showNameSearch()">
                                    Cari
                                </button>
                            </div>

                            <div class="col-lg-9" style="display: block">
                                <input type="text" class="form-control @error('creditCustomerName') is-invalid @enderror" id="tipe_handphone" wire:model="creditCustomerName" readonly>
                                <div class="list-group {{ $showNameSearch ? '' : 'd-none' }}" style="position: absolute; z-index: 1;">
                                    <input type="text" class="form-control" placeholder="Masukkan NIK" wire:model="searchName">
                                    @foreach ($creditCustomers as $customer)
                                        <button type="button" class="list-group-item list-group-item-action" wire:click="selectCustomer({{ $customer->id }},'{{ $customer->nama }}')">
                                            {{ $customer->nama }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>                       
                    </div>

                    <div class="form-group">
                        <label for="tipe_handphone">
                            <strong>Tipe Handphone</strong> 
                        </label>
                        <div class="row">
                            <div class="col-lg-3 text-center text-lg-left mb-1 mb-lg-0">
                                <button class="btn btn-secondary btn-block" type="button" wire:click="showTypeSearch()">Cari</button>
                            </div>
                            <div class="col-lg-9" style="display: block">
                                <input type="text" class="form-control @error('creditCustomerName') is-invalid @enderror" id="tipe_handphone" wire:model="merk">
                                <div class="list-group {{ $showTypeSearch ? '' : 'd-none' }}" style="position: absolute; z-index: 1;">
                                    <input type="text" class="form-control" placeholder="Masukkan NIK" wire:model="searchType">
                                    @foreach ($products as $product)
                                        <button type="button" class="list-group-item list-group-item-action" wire:click="selectProduct('{{ $product->tipe }}')">{{ $product->tipe }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>                       
                    </div>
                    <div class="form-group">
                        <label for="tenor">
                            <strong>Tenor</strong> 
                        </label>
                        <div class="row">
                            <div class="col-lg-3">
                                <input type="number" class="form-control text-right" id="tenor" min="3" max="9" wire:model="tenor">
                            </div>
                            <div class="col-lg-3">
                                <label class="my-1 ml-n4 mx-auto">bulan</label>
                            </div>
                        </div>                       
                    </div>
                    <div class="form-group">
                        <label for="dp">
                            <strong>DP</strong>
                        </label>
                        <input type="number" class="form-control" id="dp" wire:model="dp">                 
                    </div>
                    <div class="form-group">
                        <label for="angsuran">
                            <strong>Angsuran</strong>
                        </label>
                        <input type="number" class="form-control" id="angsuran" wire:model="angsuran">                 
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </form>
            </div>
        </div>
        
    </div>
</div>
