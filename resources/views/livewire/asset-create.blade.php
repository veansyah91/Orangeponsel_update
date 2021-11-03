<div>
    <div class="h4">
        Tambahkan Asset
    </div>

    <form wire:submit.prevent="store" class="mt-1">
        <div class="form-group row">
            <label for="nama" class="col-sm-3 col-form-label">Nama Item</label>
            <div class="col-sm-9">
                <input 
                    wire:model="nama" 
                    type="text" 
                    class="form-control @error('nama') is-invalid @enderror" 
                    placeholder="Nama Item"
                    id="validasi-nama" aria-describedby="umpan-balik-validasi-nama"
                >   
                @error('nama')
                    <span id="umpan-balik-validasi-nama" class="invalid-feedback">Silakan isi Nama Item</span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="validasi-jumlah" class="col-sm-3 col-form-label">Jumlah</label>
            <div class="col-sm-9">
                <input 
                    wire:model="jumlah" 
                    type="number" 
                    class="form-control @error('jumlah') is-invalid @enderror" 
                    placeholder="Jumlah"
                    id="validasi-jumlah"
                >   
            </div>
        </div>
        <div class="form-group row">
            <label for="validasi-harga" class="col-sm-3 col-form-label">Harga</label>
            <div class="col-sm-9">
                <input 
                    wire:model="harga" 
                    type="number" 
                    class="form-control @error('harga') is-invalid @enderror" 
                    placeholder="Harga"
                    id="validasi-harga"
                >   
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
</div>
