<div>
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-12 h3">
                            Update Masa Tenggang
                        </div>
                        <div class="col-lg-6 col-12 text-right">
                            <button class="btn btn-success" wire:click="back">
                                kembali
                            </button>
                        </div>
                    </div>
                    
                </div>

                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8">
                            <form wire:submit.prevent="update">
                                <div class="form-group row">
                                    <label for="nama" class="col-sm-3 col-form-label font-weight-bold">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" readonly class="form-control-plaintext" id="nama" value="{{ $nama }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="no-hp" class="col-sm-3 col-form-label font-weight-bold">Nomor HP</label>
                                    <div class="col-sm-9">
                                        <input type="text" readonly class="form-control-plaintext" id="no-hp" value="{{ $no_hp }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="terlambat" class="col-sm-3 col-form-label font-weight-bold">Terlambat</label>
                                    <div class="col-sm-9">
                                        <input type="text" readonly class="form-control-plaintext" id="terlambat" value="{{ $terlambat }} hari">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="tenggang" class="col-sm-3 col-form-label font-weight-bold">Tenggang</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control @error('tenggang') is-invalid @enderror" id="tenggang" wire:model="tenggang">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="keterangan" class="col-sm-3 col-form-label font-weight-bold">Keterangan</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" rows="3" wire:model="keterangan"></textarea>
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
