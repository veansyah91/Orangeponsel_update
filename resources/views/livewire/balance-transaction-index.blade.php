<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header h3">
                        <div class="row">
                            <div class="col">
                                Transaksi Pulsa dan PLN
                            </div>

                            @role('SUPER ADMIN')
                                <div class="col">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <select class="form-control" id="outletId" wire:model="outletId" wire:click="changeOutlet()">
                                                @foreach ($outlets as $outlet)
                                                    <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>    
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endrole
                        </div>
                        
                    </div>
                
                    {{-- @if ($showUpdate)
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-sm btn-success" wire:click="cancel()">Batal Update</button>
                            </div>
                        </div>
                    </div>
                    edit
                    <livewire:invoice-balance-edit /> 
                        
                    @else
                        
                        buat
                        <livewire:invoice-balance-create />

                    @endif --}}
                    {{-- <button wire:click="editTransaction()">Edit</button> --}}

                    @if ($showUpdate)

                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <button wire:click="cancelUpdate()" class="btn btn-sm btn-success">batal edit</button>
                                </div>
                            </div>
                        </div>

                        <livewire:balance-transaction-update :outletId="$outletId"/>

                    @else

                        <livewire:balance-transaction-create :outletId="$outletId"/>

                    @endif

                    <div class="card-body">

                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-center">Waktu</th>
                                    <th class="text-center">Server</th>
                                    <th class="text-center">No HP/Token</th>
                                    <th class="text-center">Harga Modal</th>
                                    <th class="text-center">Harga Jual</th>
                                    <th class="text-center">Keterangan</th>
                                    <th></th>
                                </tr>
                            </thead>
                
                            <tbody>
                                @php
                                    $totalModal = 0;
                                    $totalJual = 0;
                                @endphp

                                @foreach ($data as $d)
                                    <tr>
                                        <td class="text-center">{{ substr($d->updated_at,11) }}</td>
                                        <td class="text-center">{{ $d->supplier->nama }}</td>
                                        <td class="text-center">{{ $d->nomorId }}</td>
                                        <td class="text-center">
                                            @php
                                               $totalModal += $d->modal; 
                                            @endphp
                                            Rp. {{ number_format($d->modal,0,",",".") }}
                                        </td>
                                        <td class="text-center">
                                            Rp. {{ number_format($d->jual,0,",",".") }}
                                            @php
                                                $totalJual += $d->jual;
                                            @endphp
                                        </td>
                                        <td class="text-center">{{ $d->keterangan }}</td>
                                        <td>
                                            <div x-data="{ deleteShow: false, deleteHide: true }">
                                                        
                                                <div x-show="deleteHide">

                                                    <button @click="deleteShow=true;deleteHide=false" class="btn btn-sm btn-danger">Hapus</button>
                                                     
                                                    <button class="btn btn-sm btn-success" wire:click="editTransaksiSaldo({{ $d->id }})">
                                                        Ubah
                                                    </button>
                                                    
                                                </div>
                                            
                                                <div x-show="deleteShow" @click.away="deleteShow=false;deleteHide=true">

                                                    <div class="row">
                                                        <div class="col text-center">
                                                            Yakin Menghapus Data?
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <button class="btn btn-sm btn-danger" wire:click="deleteConfirmation({{ $d->id }})" @click="deleteShow=false;deleteHide=true">
                                                                Yakin
                                                            </button>
                                                            <button @click="deleteShow=false;deleteHide=true" class="btn btn-sm btn-secondary">
                                                                Batal
                                                            </button>
                                                        </div>                                                            
                                                    </div>    
                                                    
                                                </div>

                                            </div>   
                                        </td>
                                    </tr>
                                    
                                @endforeach
                                <tr>
                                    <td colspan="3">
                                        <strong>Total</strong>    
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        Rp. {{ number_format($totalModal,0,",",".") }}
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        Rp. {{ number_format($totalJual,0,",",".") }}
                                    <td class="text-right font-weight-bold">
                                        Laba : Rp. {{ number_format($totalJual-$totalModal,0,",",".") }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        {{-- {{ $data->links() }} --}}
                        
                    </div>

                    <div class="card-footer">
                        <livewire:balance-remain :outletId='$outletId'/>                        
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