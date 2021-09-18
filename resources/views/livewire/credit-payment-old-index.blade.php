<div>
    <div class="container">
        @if ($create)
            <livewire:credit-payment-old-create :partnerId="$partner_id" />     
        @elseif($detail)       
            <livewire:credit-payment-old-detail/>     
        @elseif($store)
            <livewire:credit-payment-old-store :partnerId="$partner_id" />     
        @else
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header h3">
                            Pembayaran Angsuran Kredit Lama
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-between">
                                <div class="col-6">
                                    <button class="btn btn-primary" wire:click="addButton">
                                        Tambah Data
                                    </button>
                                </div>

                                @if ($status > 0)
                                    <div class="col-6 text-right">
                                        <button class="btn btn-secondary" wire:click="storePaymentButton()">
                                            Setor Angsuran
                                        </button>
                                    </div>                                    
                                @endif
                            </div>

                            <div class="row justify-content-center mt-2">
                                <div class="col-12">
                                    <center>
                                        <table class="table table-sm table-responsive">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Nomor Nota</th>
                                                    <th class="text-center">Tanggal Bayar</th>
                                                    <th class="text-center">Nama</th>
                                                    <th class="text-center">Nomor HP</th>
                                                    <th class="text-center">Angsuran Ke-</th>
                                                    <th class="text-center">Jumlah</th>
                                                    <th class="text-center">Outlet</th>
                                                    <th class="text-center">Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($data->isNotEmpty())
                                                    @foreach ($data as $d)
                                                        <tr>
                                                            <td class="text-center">{{ $d->nomor_nota }}</td>
                                                            <td class="text-center">{{ $d->tanggal_bayar }}</td>
                                                            <td class="text-center">{{ CreditPartner::getBiodataOld($d->credit_app_old_id)["nama"] }}</td>
                                                            <td class="text-center">{{ CreditPartner::getBiodataOld($d->credit_app_old_id)["no_hp"] }}</td>
                                                            <td class="text-center">{{ $d->angsuran_ke }}</td>
                                                            <td class="text-center">Rp. {{ number_format($d->jumlah,0,",",".") }}</td>
                                                            <td class="text-center">{{ $d->outlet }}</td>
    
                                                            <td class="text-center 
                                                                @if ($d->status == '0')
                                                                    text-warning
                                                                @else
                                                                    text-primary 
                                                                @endif"
                                                            >
                                                                @if ($d->status == '0')
                                                                    pending
                                                                @else
                                                                    stored
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <div x-data="{ hapus: false, general: true }">
                                                                    <div x-show='general'>
                                                                        <button class="btn btn-sm btn-success" wire:click="showDetail({{ $d->id }})">detail</button>
            
                                                                        @if ($d->status < 1 && User::getOutletUserByOutletName(Auth::user()['id'])['nama'] == $d->outlet)
                                                                            <button class="btn btn-sm btn-danger" @click="hapus=true;general=false">hapus</button>
                                                                        @endif
                                                                        
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
                                                        <td colspan="8" class="text-center">
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
</div>
