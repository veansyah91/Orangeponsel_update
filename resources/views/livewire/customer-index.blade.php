
<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header h3">
                        Pelanggan
                    </div>
                
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                    @endif
                
                    @if ($showUpdate)
                        <div class="card-body">
                            <button class="btn btn-sm btn-success" wire:click="showCustomer()">Batal Ubah Data</button>
                        </div>

                        <livewire:customer-update />
                    @elseif ($showCreate)
                        <div class="card-body">
                            <button class="btn btn-sm btn-success" wire:click="showCustomer()">Batal Tambah Data</button>
                        </div>

                        <livewire:customer-create />

                    @else 
                        <div class="card-body">
                            <button class="btn btn-sm btn-primary" wire:click="showCreate()">Tambah Pelanggan</button>
                        </div>

                        <div class="card-body">
                            
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Telepon/Hp</th>
                                        <th class="text-center">Alamat</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                    
                                <tbody>
                                    @if ( $data->isNotEmpty())
                                        @foreach ( $data as $d )
                                            <tr>
                                                <td class="text-center" wire:key="{{ $loop->index }}">{{ $d->nama }}</td>
                                                <td class="text-center" >{{ $d->hp }}</td>
                                                <td class="text-center" >{{ $d->alamat }}</td>
                                                <td class="text-center">
                                                    <button wire:click="getCustomer({{ $d->id }})" class="btn btn-sm btn-success">Ubah</button>
                                                    <div class="btn-group dropdown">
                                                        <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Hapus
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <button class="dropdown-item active" wire:click="destroy({{ $d->id }})">Oke</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <i >Data Belum Dimasukkan</i>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{ $data->links() }}
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