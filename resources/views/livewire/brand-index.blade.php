<div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header h3">
                    Brand
                </div>
            
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      {{ session('success') }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                @endif
            
                @if ($statusUpdate)
                  <livewire:brand-update />
                @else
                  <livewire:brand-create />
                @endif
            
                <div class="card-body">

                    <div class="form-group row col">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Tampilkan</label>
                        <div class="col-sm-2">
                            <select class="custom-select" id="inputGroupSelect01" wire:model='paginate'>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                        <div class="col-sm-8 col-auto" >
                            <input type="text"class="form-control" id="search" placeholder="Cari..." wire:model="search">
                        </div>
                    </div>
                    

                    
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama Brand</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
            
                        <tbody>
                            @if ( $data->isNotEmpty())
                                @foreach ( $data as $d )
                                    <tr>
                                        <td>{{ $d->nama }}</td>
                                        <td>
                                            <button wire:click="getBrand({{ $d->id }})" class="btn btn-sm btn-success">Ubah</button>
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
                                
                            @else
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <i >Data Belum Dimasukkan</i>
                                    </td>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
            
                <script>
                    document.addEventListener('livewire:load', function () {
                        
                    })
                </script>
            </div>
        </div>
    </div>

    
</div>