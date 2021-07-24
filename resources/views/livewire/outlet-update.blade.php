

<div>
    <div class="card-body">
        <form wire:submit.prevent="update">
            <input type="hidden" wire:model="outletId">
            <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Nama Toko</label>
                <div class="col-sm-10">
                    <input 
                        wire:model="nama" 
                        type="text" 
                        class="form-control @error('nama') is-invalid @enderror" 
                        placeholder="Nama Toko"
                        id="validasi-nama" aria-describedby="umpan-balik-validasi-nama"
                    >   
                    @error('nama')
                        <span id="umpan-balik-validasi-nama" class="invalid-feedback">Silakan isi Nama Toko</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Telepon</label>
                <div class="col-sm-10">
                    <input 
                        wire:model="hp" 
                        type="text" 
                        class="form-control @error('hp') is-invalid @enderror" 
                        placeholder="Telepon"

                        
                        id="validasi-hp" aria-describedby="umpan-balik-validasi-hp"
                    >   
                </div>
            </div>
            <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10"> 
                    <textarea 
                        wire:model="alamat" 
                        class="form-control" @error('alamat') is-invalid @enderror"
                        id="validasi-alamat" 
                        rows="1" 
                        aria-describedby="umpan-balik-validasi-alamat"
                    ></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                Ubah
            </button>
            <button wire:click="cancelUpdate()"  type="button" class="btn btn-danger">Batal</button>
        </form>
    </div>
</div>

