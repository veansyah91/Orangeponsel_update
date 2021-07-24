<div>
    <div class="row justify-content-center mb-2">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header h3">
                    Ubah Pengambilan Barang Kredit
                </div>  
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-success" wire:click="cancelUpdate()">Kembali</button>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-6">
                            <form wire:submit.prevent="update()">
                                <div class="form-group" style="display:block">
                                    <label for="tipe_handphone">
                                        <strong>Nama Konsumen</strong> 
                                    </label>
                                    <div class="row">
                                        <div class="col-3">
                                            <button class="btn btn-secondary" type="button" wire:click="showNameSearch()">Cari</button>
                                        </div>
                                        <div class="col-9" style="display: block">
                                            <input type="text" class="form-control @error('creditCustomerName') is-invalid @enderror" id="tipe_handphone" wire:model="creditCustomerName" readonly required>
                                            <div class="list-group {{ $showNameSearch ? '' : 'd-none' }}" style="position: absolute; z-index: 1;">
                                                <input type="text" class="form-control" placeholder="Masukkan Nama / No HP" wire:model="search">
                                                @foreach ($creditCustomers as $customer)
                                                    <button type="button" class="list-group-item list-group-item-action" wire:click="selectCustomer({{ $customer->id }},'{{ $customer->nama }}')">
                                                        {{ $customer->nama }} / {{ $customer->no_hp }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>                       
                                </div>

                                <div class="form-group" style="display:block">
                                    <label for="tipe_handphone">
                                        <strong>Tipe Handphone</strong> 
                                    </label>
                                    <div class="row">
                                        <div class="col-3">
                                            <button class="btn btn-secondary" type="button" wire:click="showTypeSearch()">Cari</button>
                                        </div>
                                        <div class="col-9" style="display: block">
                                            <input type="text" class="form-control @error('type') is-invalid @enderror" id="tipe_handphone" wire:model="type" readonly required>
                                            <div class="list-group {{ $showTypeSearch ? '' : 'd-none' }}" style="position: absolute; z-index: 1;">
                                                <input type="text" class="form-control" placeholder="Imei/Kode" wire:model="searchType">
                                                @foreach ($products as $product)
                                                    <button type="button" class="list-group-item list-group-item-action" wire:click="selectType({{ $product->id }},'{{ $product->tipe }}')">
                                                        {{ $product->tipe }} / {{ $product->kode }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>   
                                </div>

                                <div class="form-group mt-4">
                                    <div class="row">
                                        <div class="col-3 mt-2">
                                            <strong>Harga</strong>
                                        </div>
                                        <div class="col-9">
                                            <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga_handphone" wire:model="harga">
                                        </div>
                                    </div>   
                                </div>

                                <div class="form-group mt-4">
                                    <div class="row">
                                        <div class="col-3 mt-2">
                                            <strong>Email</strong>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" wire:model="email" placeholder="Masukkan Email">
                                        </div>
                                    </div>   
                                </div>

                                <div class="form-group mt-4">
                                    <div class="row">
                                        <div class="col-3 mt-2">
                                            <strong>Password</strong>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" wire:model="password" placeholder="Masukkan Password">
                                        </div>
                                    </div>   
                                </div>

                                <div class="row">
                                    <div class="col-12 text-right">
                                        <button class="btn btn-primary" type="submit">Proses</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
