 <div>
    <div class="row justify-content-between">
        <div class="col-5">
            <div class="input-group mb-3">
                <label for="nomorNota" class="col-sm-4 col-form-label">Nota</label>
                <input 
                    wire:model="nomorNota"
                    type="number" 
                    class="form-control text-right mr-1 col-sm-4"
                    id="nomorNota" {{ $disable ? 'disabled' :'' }}
                >
                @if ($nomorNota < $nomorNotaSekarang)
                    <button class="btn btn-sm btn-success" wire:click="notaBaru()">Cari</button>
                @endif
            </div>
        </div>
        
        <div class="col-7">
            <div class="input-group mb-3">
                <label for="customer" class="col-sm-4 col-form-label text-right">Pelanggan</label>
                <div class="dropdown">
                    <input 
                        type="text" 
                        class="form-control @error('customer') is-invalid @enderror" 
                        aria-label="Sizing example input" 
                        aria-describedby="inputGroup-sizing-default" 
                        wire:model="customer"
                        wire:click="showCustomer()"
                        wire:keydown="inputCustomer()"
                        wire:keydown.escape="resetInputList()"
                        id="customer" {{ $disable ? 'disabled' :'' }}
                        autocomplete="off"
                    >

                    @error('customer')
                        <span id="umpan-balik-validasi-supplier" class="invalid-feedback">Silakan isi nama pelanggan dengan benar</span>
                    @enderror

                    <div class="btn-group">
                        <div class="dropdown-menu {{ $showCustomerInputList ? 'show' :'' }}">
                            @if ($customerInputLists->isNotEmpty())
                                @foreach ($customerInputLists as $customerInputList)
                                    <button  
                                        type="button" 
                                        class="dropdown-item" 
                                        wire:click="selectcustomer({{$customerInputList->id}},'{{$customerInputList->nama}}')"
                                        wire:keydown.enter="selectCustomer({{$customerInputList->id}},'{{$customerInputList->nama}}')">
                                        {{ $customerInputList->nama }}
                                    </button>
                                @endforeach
                            @else
                                <li class="list-group-item" >
                                    <small>Data Tidak Ditemukan <span><a target="_blank" href="{{ route('master.customer') }}" class="btn btn-sm btn-link">
                                        <i>Tambah Produk?</i>
                                    </a></span></small> 
                                </li>
                            @endif
                            
                        </div>
                    </div> 
                </div>
                
            </div>
        </div>
    </div>

    <hr>

    <div class="row justify-content-between">
        <div class="col-7">
            <div class="input-group mb-3">
                <label for="kode" class="col-sm-4 col-form-label">Kode/Imei</label>
                <div class="dropdown">
                <input wire:model="kode" 
                    type="text" 
                    class="form-control text-right @error('kode') is-invalid @enderror" 
                    placeholder="Kode/IMEI"
                    wire:click="showSearchProduct()"
                    wire:keydown="inputSearchProduct()"
                    wire:keydown.escape="resetInputList()"

                    id="validasi-kode" aria-describedby="umpan-balik-validasi-kode"

                    {{ $showProductInput ? 'disabled' : '' }}
                    autocomplete="off"
                >

                    @error('productId')
                        <span id="umpan-balik-validasi-supplier" class="invalid-feedback">Silakan isi pemasok dengan benar</span>
                    @enderror

                    <div class="btn-group">
                        <div class="dropdown-menu {{ $showProductInputList ? 'show' :'' }}">
                            @if ($productInputLists->isNotEmpty())
                                @foreach ($productInputLists as $productInputList)
                                    <button  
                                        type="button" 
                                        class="dropdown-item" 
                                        wire:click="selectProduct({{$productInputList->product_id}})"
                                        wire:keydown.enter="selectProduct({{$productInputList->product_id}})">
                                        {{ $productInputList->kode }}
                                    </button>
                                @endforeach
                            @else
                                <li class="list-group-item" >
                                    <small>Data Tidak Ditemukan <span><a href="{{ route('stok.index') }}" class="btn btn-sm btn-link">
                                        <i>Tambah Stok?</i>
                                    </a></span></small> 
                                </li>
                            @endif
                            
                        </div>
                    </div> 
                </div>
            </div>
        </div>

        <div class="col-5">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-nota">Tipe</span>
                </div>
                <input type="text" class="form-control text-right" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" wire:model="tipe" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-4">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-nota">Harga</span>
                </div>
                <input type="number" class="form-control text-right" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" wire:model="harga">
            </div>
        </div>

        <div class="col-3">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-nota">Jumlah</span>
                </div>
                <input type="number" class="form-control text-right" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" wire:model="jumlah" min="1">
            </div>
        </div>

        <div class="col-5">
            <div class="btn-group float-right" role="group" aria-label="Basic example">
                @if ($showUpdate)
                    <button type="button" class="btn btn-danger btn-lg" wire:click="cancelUpdateInvoice()">Batal</button>
                    <button type="button" class="btn btn-primary btn-lg" wire:click="updateInvoice()">Ubah</button>
                @else
                    <button type="button" class="btn btn-primary btn-lg" wire:click="saveInvoice()">Simpan</button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
    })
</script>
