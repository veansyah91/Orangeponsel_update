
<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header h3">
                        Stok
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
                        <livewire:stock-update />
                    @else
                        <livewire:stock-create />
                    @endif
                
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-4">
                                <div class="form-group row">
                                    <label for="inputGroupSelect01" class="col-sm-3 col-form-label">Show</label>
                                    <div class="col-sm-3">
                                        <select class="custom-select" id="inputGroupSelect01" wire:model='paginate'>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-3">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <select class="custom-select" id="outlet" wire:model='selectOutlet'>
                                            <option value="">-- Pilih Outlet --</option>
                                            @foreach ($outlets as $outlet)
                                                <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                                            @endforeach                                            
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-3">
                                <div class="form-group row">
                                    <label for="search" class="col-sm-1 col-form-label d-none d-md-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                        </svg>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text"class="form-control" id="search" placeholder="Cari (Kode/Tipe)" wire:model="search">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-2 text-right">
                                <a href="{{ url('/stock/pdf') }}" class="btn btn-danger btn-sm mt-auto">
                                    pdf
                                </a>
                            </div>
                            
                        </div>
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-center">Outlet</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Nomor Nota</th>
                                    <th class="text-center">Tanggal Masuk</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                
                            <tbody>
                                @if ( $data->isNotEmpty() )
                                    @foreach ( $data as $d )
                                        <tr>
                                            <td class="text-center" wire:key="{{ $loop->index }}">{{ $d->nama_outlet }}</td>
                                            <td class="text-center" >{{ $d->category_name }}</td>
                                            <td class="text-center" >{{ $d->tipe}}</td>
                                            <td class="text-center" >{{ $d->kode }}</td>
                                            <td class="text-center" >
                                                {{ $d->jumlah }}
                                            </td>
                                            <td class="text-center" >{{ ItemEntry::show($d->item_entry_id) ? ItemEntry::show($d->item_entry_id)['nomor_nota'] : ''}}</td>
                                            <td class="text-center" >{{ Date('d F Y', strtotime($d->updated_at)) }}</td>
                                            <td class="text-center">
                                                @role('SUPER ADMIN')
                                                    <button wire:click="getOutlet({{ $d->id }})" class="btn btn-sm btn-success">Ubah</button>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Hapus
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <button class="dropdown-item active" wire:click="destroy({{ $d->id }})">Oke</button>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if ($outletUser['outlet_id'] == $d->outlet_id)                                                    
                                                        <button wire:click="getOutlet({{ $d->id }})" class="btn btn-sm btn-success">Ubah</button>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Hapus
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <button class="dropdown-item active" wire:click="destroy({{ $d->id }})">Oke</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endrole                                               
                                                
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
    })
</script>