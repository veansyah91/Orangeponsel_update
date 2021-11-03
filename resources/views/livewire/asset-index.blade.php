<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header h3">
                        Asset
                    </div>

                    <div class="card-body">

                        @if ($showUpdate)
                            <livewire:asset-update />
                        @else
                            <livewire:asset-create />
                        @endif

                        <hr>

                        <div class="data mt-2">
                            <div class="text-right mb-2">
                                <a href="{{ route('stock.asset-pdf') }}" class="btn btn-sm btn-danger"> 
                                    pdf
                                </a>
                            </div>
                            <center>
                                <table class="table table-responsive">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Nama Item</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        @if ($assets->isNotEmpty())
                                            @foreach ($assets as $asset)
                                                <tr>
                                                    <td>{{ $asset->nama }}</td>
                                                    <td class="text-center">{{ $asset->jumlah }}</td>
                                                    <td class="text-right">Rp. {{ number_format($asset->harga,0,",",".") }}</td>
                                                    <td class="text-right">Rp. {{ number_format($asset->jumlah * $asset->harga,0,",",".") }}</td>
                                                    <td>
                                                        <div x-data="{ deleteShow: false, deleteHide: true }">
                                                        
                                                            <div x-show="deleteHide">
            
                                                                <button @click="deleteShow=true;deleteHide=false" class="btn btn-sm btn-danger">Hapus</button>
                                                                
                                                                <button type="button" class="btn btn-sm btn-success" wire:click="edit({{ $asset->id }})">
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
                                                                        <button class="btn btn-sm btn-danger" wire:click="deleteConfirmation({{ $asset->id }})" @click="deleteShow=false;deleteHide=true">
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
                                        @else
                                            <tr>
                                                <td class="text-center font-italic" colspan="5">Belum Ada Data</td>                                                
                                            </tr>
                                        @endif
                                        
                                        
                                    </tbody>
                                </table>
                                {{ $assets->links() }}
                            </center>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
