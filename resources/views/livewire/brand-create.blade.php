<div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="row">
                <div class="col">
                    <input 
                        wire:model="nama" 
                        type="text" 
                        class="form-control @error('nama') is-invalid @enderror" 
                        placeholder="Nama Brand"
                        autofocus
                        id="validasi-nama" aria-describedby="umpan-balik-validasi-nama"
                        autocomplete="false"
                    >
                    @error('nama')
                        <span id="umpan-balik-validasi-nama" class="invalid-feedback">Silakan isi Nama Brand</span>
                    @enderror
                </div>

                <div class="col">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>
