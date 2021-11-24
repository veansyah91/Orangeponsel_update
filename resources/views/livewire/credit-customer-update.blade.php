<div>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="p-3 container">
                <h3>Masukkan Data Calon Pelanggan</h3>
        
                <form wire:submit.prevent="update">
                    <div class="form-group mb-n1 mt-2">
                        <label for="no_ktp">No KTP (NIK)</label>
                        <input type="text" class="form-control @error('no_ktp') is-invalid @enderror" id="no_ktp" wire:model="no_ktp">
                    </div>
                    
                    @error('no_ktp')
                        <small class="form-text text-danger">Silakan Isi No KTP dengan Benar</small>
                    @enderror

                    <div class="form-group mb-n1 mt-2">
                        <label for="no_kk">No KK</label>
                        <input type="text" class="form-control @error('no_kk') is-invalid @enderror" id="no_kk" wire:model="no_kk">
                    </div>
                    @error('no_kk')
                        <small class="form-text text-danger">Silakan Isi No KK dengan Benar</small>    
                    @enderror

                    <div class="form-group mb-n1 mt-2">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" wire:model="nama">
                    </div>
                    @error('nama')
                        <small class="form-text text-danger">Silakan Isi Nama dengan Benar</small>
                    @enderror

                    <div class="form-group mb-n1 mt-2">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" wire:model="jenis_kelamin">
                            <option>-- Pilih Jenis Kelamin --</option>
                            <option value="LAKI_LAKI">LAKI-LAKI</option>
                            <option value="PEREMPUAN">PEREMPUAN</option>
                        </select>
                    </div>
                    @error('jenis_kelamin')
                        <small class="form-text text-danger">Silakan Isi Jenis Kelamin dengan Benar</small>
                    @enderror

                    <div class="form-group mb-n1 mt-2">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" wire:model="alamat">
                    </div>
                    @error('alamat')
                        <small class="form-text text-danger">Silakan Isi Alamat dengan Benar</small>
                    @enderror

                    <div class="form-group mb-n1 mt-2">
                        <label for="no_hp">No HP</label>
                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" wire:model="no_hp">
                    </div>
                    @error('no_hp')
                        <small class="form-text text-danger">Silakan Isi Nomor HP dengan Benar</small>
                    @enderror
                    <button type="submit" class="btn btn-primary" >Ubah</button>
                </form>
            </div>  
        </div>
    </div>
            
</div>

<script>
    document.addEventListener('livewire:load', function () {
    })
</script>
