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
                            @php
                                $buttonStatus = $invoices->isNotEmpty() ? '' : ' disabled';
                            @endphp

                            <div class="col-sm-6">
                                <a href="{{ url('/credit-partner/partner='. $partnerId .'/invoice-claim/to-pdf') }}" class="btn btn-primary{{$buttonStatus}}">Ajukan Nota</a>
                            </div>

                            <div class="col-sm-6 my-auto">
                                <strong>Nomor Nota: </strong> Orange-{{ $creditPartner['alias'] }}/{{ $invoiceNumber }}
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
                                                <td>Rp. {{ number_format($invoice->harga,0,",",".") }}</td>
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

                        @if ($creditPartnerInvoices->isNotEmpty())
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nomor Nota</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($creditPartnerInvoices as $creditPartnerInvoice)
                                        <tr> 
                                            @php
                                                if ($creditPartnerInvoice->status === 'waiting') 
                                                    $text_color = 'text-warning';
                                                else 
                                                    $text_color = 'text-success'
                                                
                                            @endphp
                                            <td class="text-center">{{ $creditPartnerInvoice->nomor }}</td>
                                            <td class="text-center">Rp. {{ number_format(CreditPartner::getTotalInvoice($creditPartnerInvoice->id),0,",",".") }}</td>
                                            <td class="text-center {{ $text_color }}"><strong>{{ $creditPartnerInvoice->status }}</strong></td>
                                            @if ($creditPartnerInvoice->status === 'waiting')
                                                <td class="text-center">
                                                    <div x-data="{ open: false }">
                                                        <button class="btn btn-sm btn-success" @click="open = ! open" x-show="!open">Bayar</button>

                                                        <div x-show="open">
                                                            Apakah Anda Yakin?
                                                            <button @click.outside="open = false" class="btn btn-success btn-sm" wire:click="updateStatus({{$creditPartnerInvoice->id}})">ya</button>
                                                            <button @click.outside="open = false" class="btn btn-danger btn-sm">tidak</button>
                                                        </div>
                                                    </div>
                                                    
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        @else
                            <center>
                                <i>Belum Ada Invoice</i>
                            </center>
                        @endif
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
</div>
