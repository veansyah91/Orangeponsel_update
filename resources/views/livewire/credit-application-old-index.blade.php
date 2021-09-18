<div>
    @if ($create)

        <livewire:credit-application-old-create :partnerId="$partnerId"/>

    @elseif ($edit)

        <livewire:credit-application-old-update/>

    @elseif ($showDetail)

        <livewire:credit-application-old-detail  />

    @else
    
        <div class="row justify-content-center mb-2">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 h3 col-lg-6 my-auto text-center text-lg-left">
                                Pengambilan Barang Kredit Lama
                            </div>
                            <div class="col-12 my-auto col-lg-6">
                                <input type="text" class="form-control" placeholder="cari nama / nomor hp" wire:model="search">
                            </div>
                        </div>
                        
                    </div>  
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-12">
                                <button class="btn btn-primary" wire:click="showCreate">
                                    Tambah Data
                                </button>
                            </div>
                        </div>
                        <div class="row justify-content-between mt-2">
                            <div class="col-12">
                                <center>  
                                    <table class="table table-responsive table-sm">
                                        <thead>
                                            <tr>
                                                <th>Nomor Akad</th>
                                                <th>Tgl Akad</th>
                                                <th>Nomor HP</th>
                                                <th>Nama</th>
                                                <th>Tipe HP</th>
                                                <th>Tenor</th>
                                                <th>Angsuran</th>
                                                <th>Sisa</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->isNotEmpty())
                                                @foreach ($data as $d)
                                                    <tr>
                                                        <td>{{ $d->nomor_akad }}</td>
                                                        <td>{{ $d->tanggal_akad }}</td>
                                                        <td>{{ $d->no_hp }}</td>
                                                        <td>{{ $d->nama }}</td>
                                                        <td>{{ $d->tipe }}</td>
                                                        <td>{{ $d->tenor }}</td>
                                                        <td>Rp. {{ number_format($d->angsuran) }}</td>
                                                        <td>Rp. {{ number_format($d->sisa) }}</td>
                                                        <td>
                                                            <div x-data="{ hapus: false, general: true }">
                                                                <div x-show='general'>
                                                                    <button class="btn btn-sm btn-success" wire:click="showDetail({{ $d->id }})">detail</button>
                                                                    <button class="btn btn-sm btn-warning" wire:click="edit({{ $d->id }})">ubah</button>
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
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        <i>Data Belum Ada</i>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    
                                    {{ $data->links() }}
                                </center>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
</div>

