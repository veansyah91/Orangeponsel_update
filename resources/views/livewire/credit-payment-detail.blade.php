<div>
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header h3">
                    
                    <div class="row justify-content-between">
                        <div class="col">Detail Angsuran</div>
                        <div class="col text-right">
                            <button class="btn btn-success" wire:click="backToIndex">
                                kembali
                            </button>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body">
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Nama</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $customer['nama'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>No HP</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $customer['no_hp'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Tipe HP</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $creditAppDetail['merk'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Nomor Nota BKS</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $creditPayment['nomor_pembayaran'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Jumlah Bayar</strong>                            
                        </div>
                        <div class="col-9">
                            : Rp. {{ number_format($creditPayment['jumlah'],0,",",".") }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Angsuran Ke-</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $creditPayment['angsuran_ke'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Tanggal Bayar</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $creditPayment['tanggal_bayar'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Jatuh Tempo</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $creditPayment['jatuh_tempo'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Terlambat</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $creditPayment['terlambat'] }} hari
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Outlet Pembayaran</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $creditPayment['outlet'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Pencatat Angsuran</strong>                            
                        </div>
                        <div class="col-9">
                            : {{ $creditPayment['note-taker'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-3">
                            <strong>Status</strong>                            
                        </div>
                        <div class="col-9">
                            @php
                                $status = 'belum diambil';
                                if ($creditPayment['status'] > 0) $status = 'telah diambil oleh ' . $creditPayment['sales_name']
                            @endphp
                            : {{ $status }}
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
