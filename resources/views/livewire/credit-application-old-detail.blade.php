<div>
    <div class="row justify-content-center mt-2">
        <div class="col-lg-10 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="h3">
                        Detail
                    </div> 
                    <div>
                        <button class="btn btn-success btn-sm" wire:click="backButton()">
                            kembali
                        </button>
                    </div>                      
                </div>
                <div class="card-body">

                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8">

                            <div class="row mt-1">
                                <div class="col-4">
                                    Nomor Akad
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $data['nomor_akad'] }}
                                </div>
                            </div>  
                            
                            <div class="row mt-1">
                                <div class="col-4">
                                    Tanggal Akad
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $data['tanggal_akad'] }}
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-4">
                                    Nama
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $data['nama'] }}
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-4">
                                    No Handphone
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $data['no_hp'] }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Tipe Handphone
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $data['tipe'] }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    DP
                                </div>
                                <div class="col-8 my-auto">
                                    : Rp. {{ number_format($data['dp']) }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Angsuran Per Bulan
                                </div>
                                <div class="col-8 my-auto">
                                    : Rp. {{ number_format($data['angsuran']) }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Tenor
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $data['tenor'] }} bulan
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Total Hutang
                                </div>
                                <div class="col-8 my-auto">
                                    : Rp. {{ number_format($data['total']) }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Telah Bayar
                                </div>
                                <div class="col-8 my-auto">
                                    : Rp. {{ number_format($data['total_bayar']) }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Sisa
                                </div>
                                <div class="col-8 my-auto">
                                    : Rp. {{ number_format($data['sisa']) }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Terakhir Bayar
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $data['tgl_terakhir_bayar'] }}
                                </div>
                            </div>

                        </div>
                    </div>

                    @if ($creditPayments->isNotEmpty())
                        <div class="row m-3 border-top p-3">
                            <div class="col-12">
                                <div class="h4">
                                    Pembayaran Angsuran 
                                    @if ($data['sisa'] < 1)
                                        <span class="badge badge-primary">LUNAS</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row m-1 ">
                            <div class="col-12">
                                <center>
                                    <table class="table table-sm table-responsive">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nomor Nota</th>
                                                <th class="text-center">Angsuran Ke-</th>
                                                <th class="text-center">Jumlah Bayar</th>
                                                <th class="text-center">Tanggal Bayar</th>
                                                <th class="text-center">Jatuh Tempo</th>
                                                <th class="text-center">Terlambat</th>
                                                <th class="text-center">Outlet Pembayaran</th>
                                                <th class="text-center">Collector</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($creditPayments as $creditPayment)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $creditPayment->nomor_nota }}
                                                    </td>
                                                    <td class="text-center">{{ $creditPayment->angsuran_ke }}</td>
                                                    <td class="text-center">{{ $creditPayment->jumlah }}</td>
                                                    <td class="text-center">{{ $creditPayment->tanggal_bayar }}</td>
                                                    <td class="text-center">{{ $creditPayment->jatuh_tempo }}</td>
                                                    <td class="text-center">{{ $creditPayment->terlambat }}</td>
                                                    <td class="text-center">{{ $creditPayment->outlet }}</td>
                                                    <td class="text-center">{{ $creditPayment->kolektor }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </center>
                                
                            </div>
                        </div>
                    @endif

                    

                </div>
            </div>
        </div>
    </div>
</div>
