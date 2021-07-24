<div>
    <div class="card-body">
        <form wire:submit.prevent="update">
            <div class="row">
                <div class="col">
                    <input type="hidden" wire:model="brandId">
                    <input 
                        wire:model="nama" 
                        type="text" 
                        class="form-control @error('nama') is-invalid @enderror" 
                        placeholder="Nama Toko"

                        id="validasi-nama" aria-describedby="umpan-balik-validasi-nama"
                    >
                    @error('nama')
                        <span id="umpan-balik-validasi-nama" class="invalid-feedback">Silakan isi Nama Brand</span>
                    @enderror
                </div>

                <div class="col">
                    <button type="submit" class="btn btn-primary">Ubah</button>
                    <button wire:click="cancelUpdate()"  type="button" class="btn btn-danger">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
