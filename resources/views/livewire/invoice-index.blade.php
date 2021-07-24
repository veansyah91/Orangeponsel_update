<div>
    <div class="row justify-content-center">
        
        <div class="col-lg-8">
            <div class="card">

                <div class="card-header h3">
                    <div class="row">
                        <div class="col-md-4 my-auto">Invoice</div>

                        @role("SUPER ADMIN")
                            <div class="col-md-4 offset-md-4 text-right">
                                <select class="custom-select" wire:model="selectOutlet" wire:click="selectOutlet()">
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endrole

                    </div>
                </div>
            
                <div class="card-body">
                    
                    <livewire:invoice-create :outletId="$selectOutlet"/>
                    
                    <hr>

                    <div class="justify-content-between row bg-info text-white py-2 my-3 shadow-sm">
                        <div class="col-md-8 my-auto" wire:click="uangPas()">
                            <h2><strong>Total: Rp. {{ number_format($total,0,",",".") }}</strong> </h2> 
                        </div>
                        <div class="col-md-4 text-right">
                            <button class="btn btn-light btn-lg" wire:click="showBayar()" id="show-bayar">Bayar</button> 
                        </div>
                    </div>

                    @if ($showBayar)
                        <div class="row justify-content-between">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="bayar" class="col-sm-2 col-form-label"><h3>Bayar:</h3></label>
                                    <span class="col-form-label"><h3>Rp. </h3> </span>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control-lg text-right" id="bayar" wire:model="bayar">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sisa" class="col-sm-2 col-form-label"><h3>Sisa:</h3></label>
                                    <span class="col-form-label"><h3>Rp. </h3> </span>
                                    <div class="col-sm-5">
                                        <h3 class="form-control-lg text-right">{{ number_format($sisaBayar,0,",",".") }}</h3>
                                    </div>
                                </div>
                                <button class="btn btn-primary float-right" id="print-button">Cetak</button>
                                <button class="btn btn-success float-right" wire:click="saveInvoice()">Simpan</button>
                            </div>
                        </div>
                        
                        <hr>
                    @endif

                    <div class="row justify-content-between">
                        
                        <div class="col-12">
                            <h3>Detail</h3>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center">Kode</th>
                                        <th class="text-center">Tipe</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                
                                <tbody>
                                    @if ($invoiceDetails)
                                        @foreach ($invoiceDetails as $invoiceDetail)
                                            <tr>
                                                <td>{{ $invoiceDetail->product->kode }}</td>
                                                <td>{{ $invoiceDetail->product->tipe }}</td>
                                                <td class="text-center">{{ number_format($invoiceDetail->jumlah ,0,",",".") }}</td>
                                                <td class="text-right">Rp. {{ number_format($invoiceDetail->jual ,0,",",".") }}</td>
                                                <td class="text-right">Rp. {{ number_format($invoiceDetail->jumlah * $invoiceDetail->jual ,0,",",".") }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger" wire:click="deleteInvoice({{ $invoiceDetail->id }})">Hapus</button>
                                                    @if (!$invoiceDetail->invoice->customer->outlet_id)
                                                        <button class="btn btn-sm btn-success" wire:click="getInvoice({{ $invoiceDetail->id }})">Ubah</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <i>Data Belum Diinput</i>
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

        <div class="col-lg-4">
            <livewire:invoice-today :outletId="$selectOutlet"/>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        const showBayar = document.getElementById('show-bayar')

        showBayar.addEventListener('click', function(){
            console.log(document.getElementById('print-button'))

        })

        // printButton.disabled = true
        // if (screen.width < 1024) {
        //     printButton.disabled = true
        // }
        
    })
</script>