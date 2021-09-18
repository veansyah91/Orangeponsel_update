<div>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <form wire:submit.prevent="store">
                @role('SUPER ADMIN')
                    <div class="form-group row">
                    <label for='outlet' class="col-sm-3 col-form-label">Outlet</label>
                    <div class="col-sm-8">
                        <select class="custom-select" id="inputGroupOutlets" name="outletId" wire:model='outletId'>
                            @foreach ($outlets as $outlet)
                                <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                @endrole

                <div class="form-group row">
                    <label for="supplier" class="col-sm-3 col-form-label">Server</label>
                    <div class="col-sm-8">
                        <input 
                            type="text"                     
                            class="form-control @error('supplier') is-invalid @enderror" 
                            id="supplier" 
                            name='supplier'
                            wire:model="supplier"
                            wire:click="showSearchSupplier()"
                            wire:keydown="inputSearchSupplier()"
                            wire:keydown.escape="resetListInput()"
                            required
                            autocomplete="false"
                        >
                        @error('supplier')
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
                </div>

                <div class="form-group row">
                    <label for='jumlah' class="col-sm-3 col-form-label">Jumlah</label>
                    <input type="text" readonly class="text-right form-control-plaintext col-sm-1" id="staticEmail" value="Rp. ">
                    <div class="col-sm-7">
                        <input 
                            type="number"                     
                            class="text-right form-control @error('jumlah') is-invalid @enderror" 
                            id="jumlah"
                            wire:model='jumlah'
                            autocomplete="false"
                        >
                        @error('jumlah')
                            <span id="umpan-balik-validasi-supplier" class="invalid-feedback">Silakan isi pemasok dengan benar</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for='keterangan' class="col-sm-3 col-form-label">Keterangan</label>
                    <div class="col-sm-7">
                        <input 
                            type="text"                     
                            class="text-right form-control @error('keterangan') is-invalid @enderror" 
                            id="keterangan"
                            wire:model='keterangan'
                            autocomplete="off"
                        >
                        @error('jumlah')
                            <span id="umpan-balik-validasi-supplier" class="invalid-feedback">Silakan isi pemasok dengan benar</span>
                        @enderror
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">Simpan</button>

            </form>
        </div>
    </div>     
</div>
