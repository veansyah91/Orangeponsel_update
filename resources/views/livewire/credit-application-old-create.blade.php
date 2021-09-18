<div>
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header h3 d-flex justify-content-between">       
                    <div class="col-6">
                        Masukkan Data
                    </div>  
                    <div class="col-6 text-right">
                        <button class="btn btn-secondary" wire:click="backButton">
                            kembali
                        </button>
                    </div>               
                    
                </div>

                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-12">
                            <form wire:submit.prevent="store()">
                                <div class="form-group row">
                                    <label for="nomor_akad" class="col-sm-3 col-form-label">Nomor Akad</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="nomor_akad" wire:model="nomor_akad">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('nama')is-invalid @enderror" id="nama" wire:model="nama">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="no_hp" class="col-sm-3 col-form-label">Nomor HP</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('no_hp')is-invalid @enderror" id="no_hp" wire:model="no_hp">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="tipe" class="col-sm-3 col-form-label">Tipe</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('tipe')is-invalid @enderror" id="tipe" wire:model="tipe">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="tanggal_akad" class="col-sm-3 col-form-label">Tanggal Akad</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control @error('tanggal_akad')is-invalid @enderror" id="tanggal_akad" wire:model="tanggal_akad">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="dp" class="col-sm-3 col-form-label">Down Payment</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control @error('dp')is-invalid @enderror" id="dp" wire:model="dp">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="angsuran" class="col-sm-3 col-form-label">Angsuran</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control @error('angsuran')is-invalid @enderror" id="angsuran" wire:model="angsuran">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="tenor" class="col-sm-3 col-form-label">Tenor</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control @error('tenor')is-invalid @enderror" id="tenor" wire:model="tenor">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="total" class="col-sm-3 col-form-label">Total</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control @error('total')is-invalid @enderror" id="total" wire:model="total">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="total_bayar" class="col-sm-3 col-form-label">Total Telah Bayar</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control @error('total_bayar')is-invalid @enderror" id="total_bayar" wire:model="total_bayar">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="sisa" class="col-sm-3 col-form-label">Sisa</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control @error('sisa')is-invalid @enderror" id="sisa" wire:model="sisa">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="tgl_terakhir_bayar" class="col-sm-3 col-form-label">Tanggal Terakhir Bayar</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control @error('tgl_terakhir_bayar')is-invalid @enderror" id="tgl_terakhir_bayar" wire:model="tgl_terakhir_bayar">
                                    </div>
                                </div>

                                <button class="btn btn-primary">
                                    Simpan
                                </button>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
