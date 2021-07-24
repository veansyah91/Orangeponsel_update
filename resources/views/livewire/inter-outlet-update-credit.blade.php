<div>
    <div class="card">
        <div class="card-header h4">
            
            <div class="row justify-content-between">
                <div class="col-6">
                    Ubah Setoran
                </div>
                <div class="col-5 text-right">
                    <button class="btn btn-sm btn-success" wire:click="backTo()">Kembali</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="update">
                <div class="form-group">
                    <label for="nominal">Jumlah Setoran</label>
                    <input type="number" class="form-control" id="nominal" aria-describedby="nominalHelp" min="0" wire:model="nominal">
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan" aria-describedby="keteranganHelp" wire:model="keterangan">
                </div>
    
                <button type="submit" class="btn btn-primary">Ubah</button>
            </form>
        </div>
    </div>
</div>

