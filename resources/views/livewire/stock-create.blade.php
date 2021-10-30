<div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            @role('SUPER ADMIN')
                <div class="form-group row">
                    <label for="outlet" class="col-sm-2 col-form-label">Nama Toko</label>
                    <div class="col-sm-2">
                        <select class="custom-select" id="inputGroupOutlets" wire:model='outletId'>
                            @foreach ($outlets as $outlet)
                                <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                            @endforeach
                        </select>
                        @error('outlet')
                            <span id="umpan-balik-validasi-outlet" class="invalid-feedback">Silakan isi Nama Toko</span>
                        @enderror
                    </div>
                </div>
            @endrole
            <div class="form-group row">
                <label for="kode" class="col-lg-2 col-12 col-form-label">Kode/IMEI</label>
                <div class="col-lg-4 col-12">
                    <input 
                        wire:model="kode" 
                        type="text" 
                        class="form-control @error('kode') is-invalid @enderror" 
                        placeholder="Kode/IMEI"
                        wire:click="showSearchProduct()"
                        wire:keydown="inputSearchProduct()"
                        wire:keydown.escape="resetListInput()"

                        id="validasi-kode" aria-describedby="umpan-balik-validasi-kode"
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

                                        wire:click="selectProduct({{$productInputList->id}},'{{$productInputList->kode}}','{{$productInputList->tipe}}')"
                                        wire:keydown.enter="selectProduct({{$productInputList->id}},'{{$productInputList->kode}}','{{$productInputList->tipe}}')">
                                        {{ $productInputList->kode }}
                                    </button>
                                @endforeach
                            @else
                                <li class="list-group-item" >
                                    <small>Data Tidak Ditemukan <span><a href="{{ route('master.product') }}" class="btn btn-sm btn-link">
                                        <i>Tambah Produk?</i>
                                    </a></span></small> 
                                </li>
                            @endif
                            
                        </div>
                    </div>  
                </div>

                <label for="tipe" class="col-lg-1 col-12 col-form-label">Tipe</label>
                <div class="col-lg-3 col-12">
                    <input 
                        wire:model="tipe" 
                        type="text" 
                        class="form-control @error('tipe') is-invalid @enderror" 
                        placeholder="Tipe"
                        readonly
                        id="validasi-tipe" aria-describedby="umpan-balik-validasi-tipe" 
                    >   
                </div>
                
            </div>
            <div class="form-group row">
                <label for="jumlah" class="col-2 col-form-label">Jumlah</label>
                <div class="col-lg-2 col-3"> 
                    <input 
                        wire:model="jumlah" 
                        type="number"
                        min="1"
                        class="form-control @error('kode') is-invalid @enderror" 
                        placeholder="Jumlah"

                        id="jumlah" aria-describedby="umpan-balik-validasi-kode"
                    >   
                </div>
            </div>
            

            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
</div>
