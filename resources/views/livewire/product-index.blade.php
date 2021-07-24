<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header h3">
                        Produk
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
                        <livewire:product-update />
                    @elseif($showCreate)
                        
                        <livewire:product-create />
                    @else 
                        <div class="card-body">
                            <button class="btn btn-sm btn-primary" wire:click="showCreate()">Tambah Data</button>
                        </div>

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
                                    <input type="text"class="form-control" id="search" placeholder="Cari (Kode/Tipe)" wire:model="search">
                                </div>
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Brand</th>
                                        <th class="text-center">Kode</th>
                                        <th class="text-center">Tipe</th>
                                        <th class="text-center">Modal</th>
                                        <th class="text-center">Jual</th>
                                        <th class="text-center">Pemasok</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                    
                                <tbody>
                                    @foreach ( $data as $d )
                                        <tr>
                                            <td class="text-center" wire:key="{{ $loop->index }}">{{ Category::getName($d->category_id)->nama }}</td>
                                            <td class="text-center" wire:key="{{ $loop->index }}">{{ Brand::getName($d->brand_id)->nama }}</td>
                                            <td class="text-center" >{{ $d->kode }}</td>
                                            <td class="text-center" >{{ $d->tipe }}</td>
                                            <td class="text-center" >Rp. {{ number_format($d->modal,0,",",".") }}</td>
                                            <td class="text-center" >Rp. {{ number_format($d->jual,0,",",".") }}</td>
                                            <td class="text-center" >{{ Supplier::getName($d->supplier_id)->nama }}</td>
                                            <td class="text-center">
                                                <button wire:click="getProduct({{ $d->id }})" class="btn btn-sm btn-success">Ubah</button>
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