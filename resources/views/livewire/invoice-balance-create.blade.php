<div>
    <div class="card-body">
        <form wire:submit.prevent="store">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="nomorId">Nomor Hp/Token</label>
                        <input type="text" class="form-control" id="nomorId" placeholder="Nomor Hp/Token" wire:model="nomorId" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" placeholder="Keterangan" wire:model="keterangan" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="harga_modal">Harga Modal</label>
                        <input type="number" class="form-control text-right" id="harga_modal" wire:model="modal">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="number" class="form-control text-right" id="harga_jual" wire:model="jual">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="server">Server</label>
                        <select class="form-control" id="outletId" wire:model="supplierId" required>
                            @foreach ($servers as $server)
                                <option value="{{ $server->supplier_id }}">{{ $server->nama }}</option>    
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="submit" class="text-white">Submit</label>
                        <input type='submit' class="form-control bg-primary text-white" id="submit" value="Simpan">
                    </div>
                </div>
            </div>
            
          </form>
    </div>
</div>
