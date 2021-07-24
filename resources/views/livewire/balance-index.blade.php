<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card ">
                    <div class="card-header h4">Saldo</div>
    
                    <div class="card-body">
                        @if ($editTransaksiSaldo)
                            <livewire:balance-edit /> 
                        @else
                            <livewire:balance-add /> 
                        @endif
                    </div>

                    <div class="card-header h5 text-primary font-weight-bold">Detail Transaksi</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Supllier</th>
                                            <th>Outlet</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($balanceDetails as $balanceDetail)
                                            <tr>
                                                <td>{{ $balanceDetail->updated_at }}</td>
                                                <td class="text-right">Rp. {{ number_format($balanceDetail->jumlah,0,",",".") }}</td>
                                                <td>{{ $balanceDetail->supplier->nama }}</td>
                                                <td>{{ $balanceDetail->outlet->nama }}</td>
                                                <th>
                                                    <div x-data="{ deleteShow: false, deleteHide: true }">
                                                        
                                                        <div x-show="deleteHide">

                                                            <button @click="deleteShow=true;deleteHide=false" class="btn btn-sm btn-danger">Hapus</button>    
                                                            <button class="btn btn-sm btn-success" wire:click="editTransaksiSaldo({{ $balanceDetail->id }})">
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
                                                                    <button class="btn btn-sm btn-danger" wire:click="deleteConfirmation({{ $balanceDetail->id }})">
                                                                        Yakin
                                                                    </button>
                                                                    <button @click="deleteShow=false;deleteHide=true" class="btn btn-sm btn-secondary">
                                                                        Batal
                                                                    </button>
                                                                </div>                                                            
                                                            </div>    
                                                            
                                                        </div>

                                                    </div>                                                    
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    
                                </table>
                                {{ $balanceDetails->links() }}
                            </div>
                        </div>                        
                    </div>

                    <div class="card-footer h4">
                        Sisa Saldo
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
