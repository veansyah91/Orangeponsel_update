<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header h3">
                        Pengajuan Nota
                    </div>  
                
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <button class="btn btn-primary">Ajukan Nota</button>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal Pengambilan</th>
                                            <th>Nama Konsumen</th>
                                            <th>No HP</th>
                                            <th>Kode/Imei HP</th>
                                            <th>Tipe HP</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $invoice)
                                            <tr>
                                                @php
                                                    $date = explode(' ',$invoice->created_at)
                                                @endphp
                                                <td class="text-center">{{ $date[0] }}</td>
                                                <td>{{ $invoice->nama }}</td>
                                                <td>{{ $invoice->no_hp }}</td>
                                                <td>{{ Product::show($invoice->product_id)->kode }}</td>
                                                <td>{{ Product::show($invoice->product_id)->tipe }}</td>
                                                <td>Rp. {{ number_format(Product::show($invoice->product_id)->jual,0,",",".") }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header h3">
                        History
                    </div>                     
                
                    <div class="card-body">

                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
