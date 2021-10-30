<div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header h3">
                    <div class="row justify-content-between">
                        <div class="col">Cari Konsumen</div>
                        <div class="col text-right">
                            <button class="btn btn-success" wire:click="backToIndex">
                                kembali
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="input w-100">
                        <input type="text" placeholder="masukkan nama / nomor hp" class="form-control" wire:model="search">
                    </div>
                    <hr>
                    <div class="h4">
                        Hasil
                    </div>

                    @if ($creditCustomers->isNotEmpty())
                        @foreach ($creditCustomers as $creditCustomer)
                            <div class="border border-dark mt-2 p-2">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Nama
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : {{ $creditCustomer->nama }}
                                            </div>
                                        </div>
                    
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Nomor HP
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : {{ $creditCustomer->no_hp }}
                                            </div>
                                        </div>
                
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Tipe 
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : {{ $creditCustomer->tipe }}
                                            </div>
                                        </div>
                
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Tanggal Akad
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : {{ $creditCustomer->tipe }}
                                            </div>
                                        </div>
                
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                DP
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : Rp. {{ number_format($creditCustomer->dp,0,",",".") }}
                                            </div>
                                        </div>
                
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Angsuran Per Bulan
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : Rp. {{ number_format($creditCustomer->angsuran,0,",",".") }}
                                            </div>
                                        </div>
                
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Tenor
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : {{ $creditCustomer->tenor }} Bulan
                                            </div>
                                        </div>
                
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Total
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : Rp. {{ number_format($creditCustomer->total,0,",",".") }}
                                            </div>
                                        </div>
                
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Telah Bayar
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : Rp. {{ number_format($creditCustomer->total_bayar,0,",",".") }}
                                            </div>
                                        </div>
                
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Sisa
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : Rp. {{ number_format($creditCustomer->sisa,0,",",".") }}
                                            </div>
                                        </div>
                
                                        <div class="mt-1 row ml-1">
                                            <div class="col-lg-4 text-center text-lg-left font-weight-bold">
                                                Terakhir Bayar
                                            </div>
                                            <div class="col-lg-6 text-center text-lg-left">
                                                : {{ $creditCustomer->tgl_terakhir_bayar }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <button class="btn btn-primary w-100" wire:click='selectData({{ $creditCustomer->id }})'>
                                            Pilih
                                        </button>
                                    </div>
                                    
                                </div>                                
                            </div>
                        @endforeach
                        
                    @else
                        <div class="border border-dark mt-2 p-2">
                            <center>
                                <i>
                                    No Result
                                </i>
                            </center>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
