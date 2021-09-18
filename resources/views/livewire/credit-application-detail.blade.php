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

                            {{-- Data Konsumen --}}
                            <h5>
                                <strong>Data Konsumen</strong>                                
                            </h5>

                            <div class="row mt-1">
                                <div class="col-4">
                                    Nama
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditCustomer['nama']  }}
                                </div>
                            </div>   
                            
                            <div class="row mt-2">
                                <div class="col-4">
                                    NIK
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditCustomer['no_ktp']  }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Nomor KK
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditCustomer['no_kk']  }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Jenis Kelamin
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditCustomer['jenis_kelamin']  }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Alamat
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditCustomer['alamat']  }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Nomor HP
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditCustomer['no_hp']  }}
                                </div>
                            </div>

                        </div>
                    </div>         
                    
                    <div class="row justify-content-center mt-4">
                        <div class="col-12 col-lg-8">

                            {{-- Pengajuan Kredit --}}
                            <h5>
                                <strong>Pengajuan Kredit</strong>                                
                            </h5>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Tipe Handphone
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditApplication['merk']  }}
                                </div>
                            </div>   
                            
                            <div class="row mt-2">
                                <div class="col-4">
                                    Tenor
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditApplication['tenor'] }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    DP
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditApplication['dp'] }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Angsuran
                                </div>
                                <div class="col-8 my-auto">
                                    : Rp. {{ number_format($creditApplication['angsuran']) }}
                                </div>
                            </div>

                            @php
                                $colorStatus = '';
                                $setStatus = 'menunggu';
                                if ($creditApplication['status'] == 'reject') {
                                    $colorStatus = ' text-danger';
                                    $setStatus = 'ditolak';
                                } elseif ($creditApplication['status'] == 'accept') {
                                    $colorStatus = ' text-success';
                                    $setStatus = 'diterima';
                                } elseif ($creditApplication['status'] == 'taken') {
                                    $colorStatus = ' text-primary';
                                    $setStatus = 'telah diambil';
                                }
                            @endphp

                            <div class="row mt-2">
                                <div class="col-4">
                                    Status
                                </div>
                                <div class="col-8 my-auto font-weight-bolder{{$colorStatus}}">
                                    : {{ $setStatus }}
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    Surveyor
                                </div>
                                <div class="col-8 my-auto">
                                    : {{ $creditApplication['sales_name'] }}
                                </div>
                            </div>

                        </div>
                    </div> 

                    @if ($creditApplicationInvoice)
                        <div class="row justify-content-center mt-4 border-top pt-3">
                            <div class="col-12 col-lg-8">

                                {{-- Pengambilan --}}
                                <h5>
                                    <strong>Pengambilan Barang</strong>                                
                                </h5>

                                <div class="row mt-2">
                                    <div class="col-4">
                                        Tipe Handphone
                                    </div>
                                    <div class="col-8 my-auto">
                                        : {{ $product['tipe']  }}
                                    </div>
                                </div>   

                                <div class="row mt-2">
                                    <div class="col-4">
                                        IMEI
                                    </div>
                                    <div class="col-8 my-auto">
                                        : {{ $product['kode']  }}
                                    </div>
                                </div>   

                                <div class="row mt-2">
                                    <div class="col-4">
                                        Harga
                                    </div>
                                    <div class="col-8 my-auto">
                                        : Rp. {{ number_format($creditApplicationInvoice['harga']) }}
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-4">
                                        Email
                                    </div>
                                    <div class="col-8 my-auto bg-warning">
                                        : {{ $creditApplication['email'] }}
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-4">
                                        Password
                                    </div>
                                    <div class="col-8 my-auto bg-warning">
                                        : {{ $creditApplication['password'] }}
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-4">
                                        Yang Memasukkan Data
                                    </div>
                                    <div class="col-8 my-auto">
                                        : {{ $creditApplicationInvoice['user_name'] }}
                                    </div>
                                </div>

                                @php
                                    $statusPayment = 'menunggu';
                                    $colorStatus = '';

                                    if ($creditApplicationInvoice['status'] == 'claiming') {
                                        $statusPayment = 'sedang diajukan';
                                        $colorStatus = ' text-info';
                                    } elseif ($creditApplicationInvoice['status'] == 'paid'){
                                        $statusPayment = 'lunas';
                                        $colorStatus = ' text-success ';
                                    }
                                    
                                @endphp

                                <div class="row mt-2">
                                    <div class="col-4">
                                        Status Pembayaran
                                    </div>
                                    <div class="col-8 my-auto font-weight-bolder{{ $colorStatus }}">
                                        : {{ $statusPayment }}
                                    </div>
                                </div>
                            </div>
                        </div> 
                    @endif
                            
                    <div class="row justify-content-center mt-4 border-top pt-3">
                        <div class="col-12 col-lg-8">
                            <h5>
                                <strong>
                                    Pembayaran Angsuran 
                                    @if ($creditApplication['lunas'] == '1')
                                        <span class="badge badge-primary">LUNAS</span>
                                    @endif
                                </strong>                                
                            </h5>

                            <table class="table table-responsive table-sm">
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
                                    @if ($creditPayments->isNotEmpty())
                                        @foreach ($creditPayments as $creditPayment)
                                            <tr>
                                                <td class="text-center">{{ $creditPayment->nomor_pembayaran }}</td>
                                                <td class="text-center">{{ $creditPayment->angsuran_ke }}</td>
                                                <td class="text-center">Rp. {{ number_format($creditPayment->jumlah,0,",",".") }}</td>
                                                <td class="text-center">{{ $creditPayment->tanggal_bayar }}</td>
                                                <td class="text-center">{{ $creditPayment->jatuh_tempo }}</td>
                                                <td class="text-center">{{ $creditPayment->terlambat }} hari</td>
                                                <td class="text-center">{{ $creditPayment->outlet }}</td>
                                                <td class="text-center">{{ $creditPayment->sales_name }}</td>
                                            </tr>
                                        @endforeach                                        
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="7">
                                                <i>
                                                    Belum Melakukan Pembayaran
                                                </i>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>                                
                            </table>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
