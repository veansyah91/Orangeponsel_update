<div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <form wire:submit.prevent="update" class="form-inline">
                <div class="form-group mb-2">
                    <label for="nama-role" class="sr-only">Nama Role</label>
                    <input type="text" readonly class="form-control-plaintext" id="nama-role" value="Nama Role">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="role" class="sr-only">Role</label>
                    <input type="text" class="form-control" id="role" placeholder="Masukkan Nama Role" wire:model="roleName" name="role">
                </div>
                @error('role')
                        <span id="umpan-balik-validasi-supplier" class="invalid-feedback">Silakan isi Nama Role</span>
                @enderror
                <button type="submit" class="btn btn-primary btn-sm mb-2">
                    Ubah Role
                </button>
                <button type="button" class="btn btn-danger btn-sm mb-2" wire:click="cancelUpdate()">
                    Batal
                </button>
            </form>
            
        </div>
    </div>
</div>
