<div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header h3">
                    <div class="row">
                        <div class="col-md-4 my-auto">Hutang</div>
                        @role('SUPER ADMIN')
                            <div class="col-md-4 offset-md-4 text-right">
                                <select class="custom-select" wire:model="selectOutlet">
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endrole
                    </div>
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
                        <div class="col-sm-3 font-weight-bold text-center my-auto">
                            Nomor Nota Pembelian
                        </div>
                        <div class="col-sm-3 font-weight-bold text-center my-auto">
                            Nama
                        </div>
                        <div class="col-sm-3 font-weight-bold text-center my-auto">
                            Sisa
                        </div>
                        <div class="col-sm-3 font-weight-bold text-center my-auto">
                            Aksi
                        </div>
                    </div>
                    <hr>

                    @if ($remains->isNotEmpty())

                        @foreach ($remains as $remain)

                            @if (Invoice::getDebtPaymentTotal($remain->invoice_id) != $remain->sisa * -1 && !$remain->outlet_id)
                                <livewire:debt-detail :invoiceId="$remain->invoice_id" :paymentId="$remain->id" :customerName="$remain->nama" :sisa="$remain->sisa"/>
                            @endif
                            
                        @endforeach

                    @else
                    
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <i>Tidak Ada Hutang</i>
                        </div>
                        
                    </div>
                        
                    @endif
                    
                </div>
            
                
            </div>
        </div>
    </div>

    
</div>

<script>
    document.addEventListener('livewire:load', function () {
        
    })
</script>