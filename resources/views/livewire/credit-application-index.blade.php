<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header h3">
                        Pengajuan Kredit
                    </div>  
                
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                    @endif

                    @if (session()->has('fail'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('fail') }}
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
                        <livewire:credit-application-update />
                    @elseif($showCreate)
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-success btn-sm" wire:click="cancelCreate()">Batal Tambah Data</button>
                                </div>
                            </div>
                        </div>
                        <livewire:credit-application-create :partnerId="$partnerId"/>
                    @else
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary" wire:click="create()">Tambah Data</button>
                                </div>
                            </div>
                        </div>  
                
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group row">
                                        <label for="show" class="col-sm-4 col-form-label">Tampilkan</label>
                                        <div class="col-sm-5">
                                            <select id="inputState" class="form-control" id="show" wire:model="paginate">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group row">
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control-plaintext" id="search" placeholder="Cari Nama/No KTP" wire:model="searchName">
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            

                            <table class="table table-sm table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        @role('SUPER ADMIN|SALES')
                                            <th class="text-center align-middle" style="width:150px">Keputusan</th>
                                        @endrole
                                        <th class="text-center align-middle" style="width:150px">Nama Surveyor</th>
                                        <th class="text-center align-middle">Nama Konsumen / NIK</th>
                                        <th class="text-center align-middle">Alamat</th>
                                        <th class="text-center align-middle">Tipe Handphone</th>
                                        <th class="text-center align-middle">Tenor</th>
                                        <th class="text-center align-middle">DP</th>
                                        <th class="text-center align-middle">Angsuran</th>
                                        <th class="text-center align-middle">Outlet</th>
                                        <th class="text-center align-middle">Aksi</th>
                                        
                                    </tr>
                                </thead>
                    
                                <tbody>
                                    @foreach ($applications as $application)
                                        <tr>
                                            @role('SUPER ADMIN|SALES')
                                                <td class="text-center">
                                                    @if ($application->status == "pending")
                                                        <button class="btn btn-success" wire:click="accept({{ $application->id }})">Terima</button>
                                                        <button class="btn btn-danger" wire:click="reject({{ $application->id }})">Tolak</button>
                                                    @elseif ($application->status == "taken")
                                                        Handphone Sudah Diambil
                                                    @else
                                                        <button class="btn btn-secondary" wire:click="waitList({{ $application->id }})">Masuk Daftar Tunggu</button>
                                                    @endif
                                                </td>
                                            @endrole

                                            <td class="text-center">{{ $application->sales_name}}</td>
                                            
                                            <td>{{ $application->nama }} / {{ $application->no_ktp }}</td>
                                            <td>{{ $application->alamat }}</td>
                                            <td class="text-center">{{ $application->merk }}</td>
                                            <td class="text-center">{{ $application->tenor }}</td>
                                            <td class="text-center">Rp. {{ number_format($application->dp,0,",",".") }}</td>
                                            <td class="text-center">Rp. {{ number_format($application->angsuran,0,",",".") }}</td>
                                            <td class="text-center">{{ $application->nama_outlet }}</td>
                                            <td class="text-center">
                                                @if ($application->status !== 'taken')
                                                    <div x-data="{ hapus: false, general: true }">
                                                        <div x-show='general'>
                                                            @if (!User::getOutletUser(Auth::user()->id))
                                                                <button class="btn btn-sm btn-success" wire:click='update({{ $application->id }})'>ubah</button>
                                                            @else 
                                                                @if (User::getOutletUser(Auth::user()->id)->outlet_id == $application->outlet_id)
                                                                    <button class="btn btn-sm btn-success" wire:click='update({{ $application->id }})'>ubah</button>
                                                                @endif
                                                            @endif
                                                            
                                                            @role('SUPER ADMIN')
                                                                <button class="btn btn-sm btn-danger" @click="hapus=true;general=false">hapus</button>
                                                            @endrole

                                                            @role('ADMIN|FRONT LINER')
                                                                @if (User::getOutletUser(Auth::user()->id)->outlet_id == $application->outlet_id)
                                                                    <button class="btn btn-sm btn-danger" @click="hapus=true;general=false">hapus</button>
                                                                @endif
                                                            @endrole
                                                        </div>
                                                        
                                                        @role('ADMIN|SUPER ADMIN')
                                                        <div x-show="hapus" @click.away="hapus=false">
                                                            Apakah Anda Yakin?
                                                            <div class="row">
                                                                <div class="col">
                                                                    <button class="btn btn-sm btn-secondary" @click="hapus=false;general=true">Tidak</button>
                                                                    <button class="btn btn-sm btn-danger" @click="hapus=false;general=true" wire:click="delete({{ $application->id }})">Ya</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endrole
                                                    </div>
                                                @else
                                                    Handphone telah diambil
                                                @endif
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col d-flex justify-content-center">
                                    {{ $applications->links() }}
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
