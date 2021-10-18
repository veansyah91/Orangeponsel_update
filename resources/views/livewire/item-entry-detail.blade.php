<div>
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-6 h3">
                            Detail Nota
                        </div>
                        <div class="col-12 col-md-6 text-right">
                            <button class="btn btn-sm btn-secondary" wire:click="backButton">kembali</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="detail-info ">
                        <div class="row justify-content-between">
                            <div class="col-lg-6 col-12">
                                <div class="row mt-2">
                                    <div class="col-4 font-weight-bold">
                                        Nomor Nota  
                                    </div>
                                    <div class="col-8">
                                        : {{ $itemEntry['nomor_nota'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="row mt-2">
                                    <div class="col-4 font-weight-bold">
                                        Tanggal Masuk 
                                    </div>
                                    <div class="col-8">
                                        : {{ $itemEntry['tanggal_masuk'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="detail-product mt-3">
                        <h4>Daftar Barang</h4>

                        @if ($showUpdate)
                            <livewire:item-entry-list-edit/> 
                        @else
                            <livewire:item-entry-list-create/> 
                        @endif                        

                        <div class="list-product mt-3">
                            <table class="table table-reponsive">
                                <thead>
                                    <tr class="text-center">
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($stocks->isNotEmpty())
                                        @foreach ($stocks as $stock)
                                            <tr class="text-center">
                                                <td>{{ $stock->product->kode }}</td>
                                                <td>{{ $stock->product->tipe }}</td>
                                                <td>{{ $stock->jumlah }}</td>
                                                <td>
                                                    <div x-data="{ deleteShow: false, deleteHide: true }">
                                                        
                                                        <div x-show="deleteHide">
        
                                                            <button @click="deleteShow=true;deleteHide=false" class="btn btn-sm btn-danger">Hapus</button>
                                                             
                                                            <button class="btn btn-sm btn-success" wire:click="edit({{ $stock->id }})">
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
                                                                    <button class="btn btn-sm btn-danger" wire:click="deleteConfirmation({{ $stock->id }})" @click="deleteShow=false;deleteHide=true">
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
                                        <tr class="text-center" >
                                            <td colspan="3">
                                                <i>Data Kosong</i>
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
    </div>
</div>
