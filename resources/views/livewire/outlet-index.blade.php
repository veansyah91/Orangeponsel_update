
<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header h3">
                        Outlet 
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
                        <livewire:outlet-update />
                    @else
                        <livewire:outlet-create />
                    @endif
                
                    <div class="card-body">
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Telepon/Hp</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                
                            <tbody>
                                @foreach ( $data as $d )
                                    <tr>
                                        <td class="text-center" wire:key="{{ $loop->index }}">{{ $d->nama }}</td>
                                        <td class="text-center" >{{ $d->hp }}</td>
                                        <td class="text-center" >{{ $d->alamat }}</td>
                                        <td class="text-center">
                                            <button wire:click="getOutlet({{ $d->id }})" class="btn btn-sm btn-success">Ubah</button>
                                            <div class="btn-group">
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
                            </tbody>
                        </table>
                        
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