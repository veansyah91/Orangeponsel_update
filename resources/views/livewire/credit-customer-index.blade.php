
<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header h3">
                        Pengajuan Data Konsumen Kredit
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
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-success btn-sm" wire:click="cancelUpdate()">Batal Ubah Data</button>
                                </div>
                            </div>
                        </div>
                        <livewire:credit-customer-update />
                    @elseif ($showCreate)
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-success btn-sm" wire:click="cancelCreate()">Batal Tambah Data</button>
                                </div>
                            </div>
                        </div>
                        <livewire:credit-customer-create />
                    @else

                    @role('SUPER ADMIN|ADMIN|FRONT LINER|PROMOTOR')
                        <div class="card-body mb-n2">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary" wire:click="showCreate()">Tambah Data</button>
                                </div>
                            </div>
                        </div>
                    @endrole
                
                    <div class="card-body">

                        <div class="row justify-content-between">
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label for="paginate" class="col-sm-3 col-form-label mr-n3">Tampilkan</label>
                                    <select class="col-sm-2 form-control" id="paginate" wire:model="paginate">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="search" wire:model="search" placeholder="Cari...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <table class="table table-responsive">
                            <thead>
                                <tr class="align-middle">
                                    <th class="text-center align-middle">Nama Pelanggan Kredit</th>
                                    <th class="text-center align-middle">No KTP (NIK)</th>
                                    <th class="text-center align-middle">No KK</th>
                                    <th class="text-center align-middle">Jenis Kelamin</th>
                                    <th class="text-center align-middle">Alamat</th>
                                    <th class="text-center align-middle">No HP</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @if ($data->isNotEmpty())
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($data as $d)
                                        <tr>
                                            <td>{{ $d->nama }}</td>
                                            <td>{{ $d->no_ktp }}</td>
                                            <td>{{ $d->no_kk }}</td>
                                            <td>{{ $d->jenis_kelamin }}</td>
                                            <td>{{ $d->alamat }}</td>
                                            <td>{{ $d->no_hp }}</td>
                                            <td>
                                                @role("SUPER ADMIN")
                                                    <div x-data="{ hapus: false, general: true }">
                                                        <div x-show='general'>
                                                            <button class="btn btn-sm btn-success" wire:click='update({{ $d->id }})'>ubah</button>
                                                            <button class="btn btn-sm btn-danger" @click="hapus=true;general=false">hapus</button>
                                                        </div>
                                                    
                                                        <div x-show="hapus" @click.away="hapus=false">
                                                            Apakah Anda Yakin?
                                                            <div class="row">
                                                                <div class="col">
                                                                    <button class="btn btn-sm btn-secondary" @click="hapus=false;general=true">Tidak</button>
                                                                    <button class="btn btn-sm btn-danger" @click="hapus=false;general=true" wire:click="delete({{ $d->id }})">Ya</button>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                @endrole
                                                
                                                @if (User::getOutletUser(Auth::user()->id))
                                                    @if (User::getOutletUser(Auth::user()->id)->outlet_id == $d->outlet_id)
                                                        <div x-data="{ hapus: false, general: true }">
                                                            <div x-show='general'>
                                                                <button class="btn btn-sm btn-success" wire:click='update({{ $d->id }})'>ubah</button>
                                                                <button class="btn btn-sm btn-danger" @click="hapus=true;general=false">hapus</button>
                                                            </div>
                                                        
                                                            <div x-show="hapus" @click.away="hapus=false">
                                                                Apakah Anda Yakin?
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <button class="btn btn-sm btn-secondary" @click="hapus=false;general=true">Tidak</button>
                                                                        <button class="btn btn-sm btn-danger" @click="hapus=false;general=true" wire:click="delete({{ $d->id }})">Ya</button>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                                
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                @else 
                                    <tr>
                                        <td colspan="9">
                                            <i>Data Konsumen Belum Diinput</i>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col d-flex justify-content-center">
                                {{ $data->links() }}
                            </div>
                        </div>
                        
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
