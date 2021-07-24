<div>
    <div class="card-body">
            <button class="btn btn-sm btn-success" wire:click="showProduct()">Ke Halaman Produk</button>
            <button class="btn btn-sm btn-secondary float-right" wire:click="resetAllInput()">Bersihkan Kolom Input</button>
        
        
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    @endif

    <div class="card-body">
        <form wire:submit.prevent="storeProduct()">
            <div class="form-row">
                <div class="form-group col-md-3 ">
                    <label for="supplier">Pemasok</label>
                    <input 
                        type="text"                     
                        class="form-control @error('supplierId') is-invalid @enderror" 
                        id="supplier" 
                        wire:model="supplier"
                        wire:click="showSearchSupplier()"
                        wire:keydown="inputSearchSupplier()"
                        wire:keydown.escape="resetListInput()"
                    >
                    @error('supplierId')
                        <span id="umpan-balik-validasi-supplier" class="invalid-feedback">Silakan isi pemasok dengan benar</span>
                    @enderror
                    <div class="btn-group">
                        <div class="dropdown-menu {{ $supplierInputList ? ' show' :'' }}">
                            @if ($supplierInputLists->isNotEmpty())
                                @foreach ($supplierInputLists as $supplierInputList)
                                    <button  
                                        type="button" 
                                        class="dropdown-item" 
                                        wire:click="selectSupplier({{$supplierInputList->id}},'{{$supplierInputList->nama}}')"
                                        wire:keydown.enter="selectSupplier({{$supplierInputList->id}},'{{$supplierInputList->nama}}')">
                                        {{ $supplierInputList->nama }}
                                    </button>
                                    
                                @endforeach
                            @else
                            <li class="list-group-item" >
                                <small>Data Tidak Ditemukan <span><a href="{{ route('master.supplier') }}" class="btn btn-sm btn-link">
                                    <i>Tambah Pemasok?</i>
                                </a></span></small> 
                            </li>
                                
                            @endif
                            
                        </div>
                    </div>

                </div>

                <div class="form-group col-md-3">
                    <label for="category">Kategori</label>
                    <input 
                        type="text"                     
                        class="form-control @error('categoryId') is-invalid @enderror" 
                        id="category" 
                        wire:model="category"
                        wire:click="showSearchCategory()"
                        wire:keydown="inputSearchCategory()"
                        wire:keydown.escape="resetListInput()"
                    >
                    @error('categoryId')
                        <span id="umpan-balik-validasi-category" class="invalid-feedback">Silakan isi kategori dengan benar</span>
                    @enderror
                    <div class="btn-group">
                        <div class="dropdown-menu {{ $categoryInputList ? ' show' :'' }}">
                            @if ($categoryInputLists->isNotEmpty())
                                @foreach ($categoryInputLists as $categoryInputList)
                                    <button  
                                        type="button" 
                                        class="dropdown-item" 
                                        wire:click="selectCategory({{$categoryInputList->id}},'{{$categoryInputList->nama}}')"
                                        wire:keydown.enter="selectCategory({{$categoryInputList->id}},'{{$categoryInputList->nama}}')">
                                        {{ $categoryInputList->nama }}
                                    </button>
                                @endforeach
                            @else
                                <li class="list-group-item" >
                                    <small>Data Tidak Ditemukan <span><a href="{{ route('master.category') }}" class="btn btn-sm btn-link">
                                        <i>Tambah Kategori?</i>
                                    </a></span></small> 
                                </li>
                            @endif
                            
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="brand">Brand</label>
                    <input 
                        type="text"                     
                        class="form-control @error('brandId') is-invalid @enderror" 
                        id="brand" 
                        wire:model="brand"
                        wire:click="showSearchBrand()"
                        wire:keydown="inputSearchBrand()"
                        wire:keydown.escape="resetListInput()"
                    >
                    @error('brandId')
                        <span id="umpan-balik-validasi-brand" class="invalid-feedback">Silakan isi kategori dengan benar</span>
                    @enderror
                    <div class="btn-group">
                        <div class="dropdown-menu {{ $brandInputList ? ' show' :'' }}">
                            @if ($brandInputLists->isNotEmpty())
                                @foreach ($brandInputLists as $brandInputList)
                                    <button  
                                        type="button" 
                                        class="dropdown-item" 
                                        wire:click="selectBrand({{$brandInputList->id}},'{{$brandInputList->nama}}')"
                                        wire:keydown.enter="selectBrand({{$brandInputList->id}},'{{$brandInputList->nama}}')">
                                        {{ $brandInputList->nama }}
                                    </button>
                                @endforeach
                            @else
                                <li class="list-group-item" >
                                    <small>Data Tidak Ditemukan <span><a href="{{ route('master.brand') }}" class="btn btn-sm btn-link">
                                        <i>Tambah Brand?</i>
                                    </a></span></small> 
                                </li>
                            @endif
                            
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="tipe">Tipe</label>
                    <input wire:model="tipe" type="text" class="form-control @error('tipe') is-invalid @enderror" id="tipe">
                    @error('tipe')
                        <span id="umpan-balik-validasi-tipe" class="invalid-feedback">Silakan isi tipe dengan benar</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                
                <div class="form-group col-md-3">
                    <label for="modal">Modal</label>
                    <input wire:model="modal" type="number" min="1000" class="form-control" id="modal" value="1000">
                </div>
                <div class="form-group col-md-3">
                    <label for="jual">Jual</label>
                    <input wire:model="jual" type="number" min="1000" class="form-control" id="jual" value="1000">
                </div>
                
                <div class="form-group col-md-3">
                    <label for="kode">Kode/IMEI</label>
                    <input wire:model="kode" type="text" class="form-control @error('kode') is-invalid @enderror" id="kode">
                    @error('kode')
                        <span id="umpan-balik-validasi-kode" class="invalid-feedback">Silakan isi kode dengan benar</span>
                    @enderror
                </div>
                
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        
        </form>
    </div>
</div>



