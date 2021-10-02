<div>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-6 h3">
                            Buat Nota Baru
                        </div>
                        <div class="col-12 col-md-6 text-right">
                            <button class="btn btn-sm btn-secondary" wire:click="backButton">
                                kembali
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="store">
                        <div class="form-group row">
                            <label for="nomor_nota" class="col-sm-3 col-form-label font-weight-bold">Nomor Nota</label>
                            <div class="col-sm-9">
                                <input 
                                    wire:model="nomor_nota" 
                                    type="text" 
                                    class="form-control @error('nomor_nota') is-invalid @enderror" 
                                    placeholder="Nomor Nota"
                                    id="validasi-nomor_nota" aria-describedby="umpan-balik-validasi-nomor-nota"
                                >   
                                @error('nomor_nota')
                                    <span id="umpan-balik-validasi-nomor-nota" class="invalid-feedback">Silakan isi Nomor Nota</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tanggal_masuk" class="col-sm-3 col-form-label font-weight-bold">Tanggal Masuk</label>
                            <div class="col-sm-9">
                                <input 
                                    wire:model="tanggal_masuk" 
                                    type="date" 
                                    class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                    id="validasi-tanggal_masuk" aria-describedby="umpan-balik-validasi-tanggal-masuk"
                                >   
                                @error('tanggal_masuk')
                                    <span id="umpan-balik-validasi-tanggal-masuk" class="invalid-feedback">Silakan isi Tanggal Masuk</span>
                                @enderror
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
