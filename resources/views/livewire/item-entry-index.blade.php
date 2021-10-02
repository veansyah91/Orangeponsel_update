<div>
    <div class="container">
        @if ($create)
            <livewire:item-entry-create />
        @elseif ($update)
            <livewire:item-entry-update />
        @elseif ($detail)
            <livewire:item-entry-detail />
        @else
            <div class="row justify-content-center">
                <div class="col-10">
                    <div class="card">
                        <div class="card-header h3">
                            Barang Masuk
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row justify-content-between">
                                        <div class="col-12 col-lg-6 my-auto">
                                            <button class="btn btn-sm btn-primary" wire:click="createData">Nota Baru</button>
                                        </div>
                                        <div class="col-12 col-lg-6 text-right my-auto">
                                            <input type="text" class="form-control" placeholder="masukkan nomor nota" wire:model='search'>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <table class="table table-responsive-sm">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Nomor Nota</th>
                                                <th>Tanggal Masuk</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($itemEntries->isNotEmpty())
                                                @foreach ($itemEntries as $itemEntry)
                                                    <tr class="text-center">
                                                        <td>{{ $itemEntry->nomor_nota }}</td>
                                                        <td>{{ $itemEntry->tanggal_masuk }}</td>
                                                        <td>
                                                            <div x-data="{ deleteShow: false, deleteHide: true }">
                                                        
                                                                <div x-show="deleteHide">
                
                                                                    <button @click="deleteShow=true;deleteHide=false" class="btn btn-sm btn-danger">Hapus</button>
                                                                    
                                                                    <button type="button" class="btn btn-sm btn-success" wire:click="edit({{ $itemEntry->id }})">
                                                                        Ubah
                                                                    </button>

                                                                    <button type="button" class="btn btn-sm btn-info" wire:click="show({{ $itemEntry->id }})">
                                                                        detail
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
                                                                            <button class="btn btn-sm btn-danger" wire:click="deleteConfirmation({{ $itemEntry->id }})" @click="deleteShow=false;deleteHide=true">
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
                                                <tr class="text-center">
                                                    <td colspan="3"><i>Data Kosong</i></td>
                                                </tr>
                                            @endif
                                            
                                        </tbody>
                                    </table>
                                    {{ $itemEntries->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</div>
