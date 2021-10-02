<div>
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-12 h3">
                            Detail Pembayaran
                        </div>
                        <div class="col-lg-6 col-12 text-right">
                            <button class="btn btn-success" wire:click="backToIndex">kembali</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Nama</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $customer['nama'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>No HP</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $customer['no_hp'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Tipe HP</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $customer['tipe'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Nomor Nota BKS</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $customer['nomor_pembayaran'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Jumlah Bayar</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : Rp. {{ number_format($data['jumlah'],0,",",".") }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Angsuran Ke-</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $data['angsuran_ke'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Tanggal Bayar</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $data['tanggal_bayar'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Jatuh Tempo</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $data['jatuh_tempo'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Terlambat</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $data['terlambat'] }} hari
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Outlet Pembayaran</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $data['outlet'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Pencatat Angsuran</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            : {{ $data['pencatat'] }}
                        </div>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4 col-md-3">
                            <strong>Status</strong>                            
                        </div>
                        <div class="col-8 col-md-9 my-auto">
                            @php
                                $status = 'belum diambil';
                                if ($data['status'] > 0) $status = 'telah diambil oleh ' . $data['kolektor']
                            @endphp
                            : {{ $status }}
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
