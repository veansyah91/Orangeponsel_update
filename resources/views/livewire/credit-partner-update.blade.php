<div>
    <div class="card-body">
        <form wire:submit.prevent="update">
            <div class="form-group row">
                <label foalamat" class="col-sm-2 col-form-label">Nama Mitra</label>
                <div class="col-sm-10">
                    <input 
                        wire:model="nama" 
                        type="text" 
                        class="form-control @error('nama') is-invalid @enderror" 
                        placeholder="Nama Mitra"
                        id="validasi-nama" aria-describedby="umpan-balik-validasi-nama"
                    >   
                    @error('nama')
                        <span id="umpan-balik-validasi-nama" class="invalid-feedback">Silakan isi Nama Mitra</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="alias" class="col-sm-2 col-form-label">Alias</label>
                <div class="col-sm-10"> 
                    <textarea 
                        wire:model="alias" 
                        class="form-control" @error('alias') is-invalid @enderror"
                        id="validasi-alias" 
                        rows="1" 
                        aria-describedby="umpan-balik-validasi-alias"
                    ></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
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


            <button type="submit" class="btn btn-primary">Ubah</button>
        </form>
    </div>
</div>