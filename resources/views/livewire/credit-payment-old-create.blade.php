<div>
    @if ($show_search)
        <livewire:credit-payment-old-search :partnerId="$partner_id"/>
    @else
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header h3">
                        <div class="row justify-content-between">
                            <div class="col">Buat Pembayaran Angsuran</div>
                            <div class="col text-right">
                                <button class="btn btn-success" wire:click="backToIndex">
                                    kembali
                                </button>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="store">
                            <div class="form-group" style="display:block">
                                <label for="tipe_handphone">
                                    <strong>Nama Konsumen</strong> 
                                </label>
                                <div class="row">
                                    <div class="col-lg-3 text-center text-lg-left mb-1 mb-lg-0">
                                        <button class="btn btn-secondary btn-block" type="button" wire:click="showNameSearch()">Cari</button>
                                    </div>
                                    <div class="col-lg-9" style="display: block">
                                        <div class="w-100">
                                            <input type="text" class="form-control @error('creditCustomerName') is-invalid @enderror" id="tipe_handphone" wire:model="creditCustomerName" readonly>
                                        </div>
                                    </div>
                                </div>                       
                            </div>
                            <div class="form-group row">
                                <label for="No HP" class="col-sm-3 col-form-label">
                                    <strong>No HP</strong> 
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="No HP" wire:model="creditCustomerPhone" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="No HP" class="col-sm-3 col-form-label">
                                    <strong>Jenis HP</strong> 
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="tipeHP" wire:model="tipeHp" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nomor_pembayaran" class="col-sm-3 col-form-label">
                                    <strong>Nomor Nota</strong> 
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('nomor_pembayaran') is-invalid @enderror" id="nomor_pembayaran" wire:model="nomor_pembayaran" name="nomor_pembayaran">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="angs_ke" class="col-sm-3 col-form-label">
                                    <strong>Angs Ke-</strong> 
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('angsuran_ke') is-invalid @enderror" id="angs-ke" wire:model="angsuran_ke">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jumlah" class="col-sm-3 col-form-label">
                                    <strong>Jumlah Bayar</strong> 
                                </label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" wire:model="jumlah" placeholder="{{ $jumlahSeharusnya   }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jumlah" class="col-sm-3 col-form-label">
                                    <strong>Tanggal Bayar</strong> 
                                </label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" wire:model="tanggal">
                                </div>
                            </div>
                            <button class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
</div>
