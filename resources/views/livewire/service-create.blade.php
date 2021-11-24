<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between">
                            <div class="col-6 h3 my-auto">
                                Tambah Data
                            </div>
                            <div class="col-6 text-right my-auto">
                                <button class="btn btn-sm btn-secondary" wire:click="cancelFunc">
                                    kembali
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form wire:submit.prevent="store">
                            <div class="form-group row">
                                <label for="nomor" class="col-sm-3 col-form-label">Nomor</label>
                                <div class="col-sm-9">
                                    <input 
                                        wire:model="nomor" 
                                        type="text" 
                                        class="form-control @error('nomor') is-invalid @enderror" 
                                        placeholder="Nomor"
                                        id="validasi-nomor" aria-describedby="umpan-balik-validasi-nama"
                                    >   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-sm-3 col-form-label">Nama Pelanggan</label>
                                <div class="col-sm-9">
                                    <input 
                                        wire:model="nama" 
                                        type="text" 
                                        class="form-control @error('nama') is-invalid @enderror" 
                                        placeholder="Nama Pelanggan"
                                        id="validasi-nama" aria-describedby="umpan-balik-validasi-nama"
                                    >   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_hp" class="col-sm-3 col-form-label">Telepon</label>
                                <div class="col-sm-9">
                                    <input 
                                        wire:model="no_hp" 
                                        type="text" 
                                        class="form-control @error('no_hp') is-invalid @enderror" 
                                        placeholder="Telepon"
                
                                        
                                        id="no_hp" aria-describedby="umpan-balik-validasi-hp"
                                    >   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tipe" class="col-sm-3 col-form-label">Tipe</label>
                                <div class="col-sm-9">
                                    <input 
                                        wire:model="tipe" 
                                        type="text" 
                                        class="form-control @error('tipe') is-invalid @enderror" 
                                        placeholder="Tipe"
                
                                        
                                        id="tipe" aria-describedby="umpan-balik-validasi-hp"
                                    >   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                                <div class="col-sm-9"> 
                                    <textarea 
                                        wire:model="keterangan" 
                                        class="form-control @error('keterangan') is-invalid @enderror"
                                        id="validasi-keterangan" 
                                        rows="1" 
                                        aria-describedby="umpan-balik-validasi-keterangan"
                                    ></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tanggal_masuk" class="col-sm-3 col-form-label">Tanggal Masuk</label>
                                <div class="col-sm-9">
                                    <input 
                                        wire:model="tanggal_masuk" 
                                        type="date" 
                                        class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                        
                                        id="tanggal_masuk" aria-describedby="umpan-balik-tanggal_masuk"
                                    >   
                                </div>
                            </div>
                
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
