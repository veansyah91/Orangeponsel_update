<div>
    <div class="row">
        <div class="col-sm-6">
            @if ($showCredit)
                <livewire:inter-outlet-invoice />
            @elseif ($createCredit)
                <livewire:inter-outlet-create-credit />
            @elseif ($updateCredit)
                <livewire:inter-outlet-update-credit />
            @else
                <div class="card">
                    <div class="card-header h4">
                        <div class="row justify-content-between">
                            <div class="col-6">
                                Piutang/Setoran
                            </div>
                            <div class="col-5 text-right">
                                <button class="btn btn-sm btn-primary" wire:click="createCredit()">Buat Setoran</button>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 overflow-auto text-center" style="max-height: 500px;">
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($credits->isNotEmpty())
                                            @foreach ($credits as $detail)
                                                <tr class="text-center">
                                                    <td>{{ $detail->updated_at }}</td>
                                                    <td>
                                                        @if ($detail->invoice_id)
                                                            <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#invoiceDetailModal" 
                                                            wire:click="showCredit({{ $detail->invoice_id }})"
                                                            >
                                                                Nomor Nota {{ $detail->invoice->no_nota }}
                                                            </button>
                                                        @else
                                                            {{ $detail->keterangan }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($detail->invoice_id)
                                                            Rp. {{ number_format(Invoice::getDetailInvoicePayment($detail->invoice_id)->sisa * -1,0,",",".") }}
                                                        @else
                                                            Rp. {{ number_format($detail->jumlah,0,",",".") }} 
                                                            <button class="btn btn-sm btn-link" wire:click="updateCredit({{ $detail->id }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="text-center">
                                                <td colspan="3"><i>Belum ada transaksi</i></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    
                                    
                                </table>

                                @if ($countCredits > $totalShowCredit)
                                    <button class="btn btn-primary btn-sm" wire:click="showOtherCredits()">Tampilkan Lainnya</button>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-footer h3">
                        <div class="row">
                            <div class="col-sm-6">Total: </div>
                            <div class="col-sm-6 text-right">Rp. {{ number_format($sumCredits,0,",",".") }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-sm-6">
            @if ($showDebt)
                <livewire:inter-outlet-invoice/>
            @else
                <div class="card">
                    <div class="card-header h4">
                        Hutang
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 overflow-auto text-center" style="max-height: 500px;">
                                <table class="table table-sm">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col"></th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($debts->isNotEmpty())
                                            @foreach ($debts as $detail)
                                                <tr class="text-center">
                                                    <td>
                                                        @if ($detail->konfirmasi == "check")
                                                            <button class="btn btn-danger btn-sm" wire:click="checked({{ $detail->id }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question" viewBox="0 0 16 16">
                                                                    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                                                                </svg>
                                                            </button>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check text-success" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z"/>
                                                            </svg>
                                                        @endif
                                                        
                                                    </td>
                                                    <td>{{ $detail->updated_at }}</td>
                                                    <td>
                                                        @if ($detail->invoice_id)
                                                            <button type="button" class="btn btn-link btn-sm"
                                                            wire:click="showDebt({{ $detail->invoice_id }})"
                                                            >
                                                                Nomor Nota {{ $detail->invoice->no_nota }}
                                                            </button>
                                                        @else
                                                            {{ $detail->keterangan }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($detail->invoice_id)
                                                            Rp. {{ number_format(Invoice::getDetailInvoicePayment($detail->invoice_id)->sisa * -1,0,",",".") }}
                                                            
                                                        @else
                                                            Rp. {{ number_format($detail->jumlah,0,",",".") }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                        @else
                                            <tr class="text-center">
                                                <td colspan="3"><i>Belum ada transaksi</i></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                                @if ($countDebts > $totalShowDebt)
                                    <button class="btn btn-primary btn-sm" wire:click="showOtherDebts()">Tampilkan Lainnya</button>
                                @endif
                                
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-footer h3">
                        <div class="row">
                            <div class="col-sm-6">Total: </div>
                            <div class="col-sm-6 text-right">Rp. {{ number_format($sumDebts,0,",",".") }}</div>
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header h3 font-weight-bolder">
                    <div class="row">
                        <div class="col-sm-6">Sisa: </div>
                        <div class="col-sm-6 text-right">Rp. {{ number_format($sumCredits-$sumDebts,0,",",".") }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('livewire:load', function () {
        
    })
</script>
