<div>
    <div class="container">

        @if ($create_payment)
            <livewire:credit-payment-create :partnerId="$partner_id" />
        @elseif($detail_payment)
            <livewire:credit-payment-detail />
        @elseif($store_payment)
            <livewire:credit-payment-store :partnerId="$partner_id"/>
        @else
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header h3">
                            Pembayaran Angsuran
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-between">

                                <div class="col-12 col-lg-6">
                                    <div class="row justify-content-between">
                                        <div class="col-12 col-lg-6">
                                            <button class="btn btn-primary w-100" wire:click="createPaymentButton()">
                                                Bayar Angsuran
                                            </button>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <small class="text-danger text-center"><i>menu ini untuk pengambilan unit diatas tanggal 18 september 2021</i> </small>
                                        </div>
                                    </div>
                                    
                                </div>

                                @if ($status > 0)
                                    <div class="col-6 text-right">
                                        <button class="btn btn-secondary" wire:click="storePaymentButton()">
                                            Setor Angsuran
                                        </button>
                                    </div>                                    
                                @endif
                                
                            </div>
                            

                            <table class="table table-sm table-responsive-sm mt-3 table-hover table-bordered">
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
                                    @if ($creditPayments->isNotEmpty())
                                        @foreach ($creditPayments as $creditPayment)
                                            <tr>
                                                <td class="text-center">{{ $creditPayment->nomor_pembayaran }}</td>
                                                <td class="text-center">{{ $creditPayment->tanggal_bayar }}</td>
                                                <td class="text-center">{{ CreditPartner::getBiodata($creditPayment->credit_application_id)["nama"] }}</td>
                                                <td class="text-center">{{ CreditPartner::getBiodata($creditPayment->credit_application_id)["no_hp"] }}</td>
                                                <td class="text-center">{{ $creditPayment->angsuran_ke }}</td>
                                                <td class="text-center">Rp. {{ number_format($creditPayment->jumlah,0,",",".") }}</td>
                                                <td class="text-center">{{ $creditPayment->outlet }}</td>
                                                
                                                @if ($creditPayment->status > 0)
                                                    <td class="text-center text-info text-bold">
                                                        stored
                                                    </td>
                                                @else
                                                    <td class="text-center text-warning text-bold">
                                                        pending
                                                    </td>
                                                @endif
                                                
                                                <td class="text-center">
                                                    <div x-data="{ hapus: false, general: true }">
                                                        <div x-show='general'>
                                                            <button class="btn btn-sm btn-success" wire:click="showDetail({{ $creditPayment->id }})">detail</button>

                                                            @if ($creditPayment->status < 1 && User::getOutletUserByOutletName(Auth::user()['id'])['nama'] == $creditPayment->outlet)
                                                                <button class="btn btn-sm btn-danger" @click="hapus=true;general=false">hapus</button>
                                                            @endif
                                                            
                                                        </div>
                                                    
                                                        <div x-show="hapus" @click.away="hapus=false">
                                                            Apakah Anda Yakin?
                                                            <div class="row">
                                                                <div class="col">
                                                                    <button class="btn btn-sm btn-secondary" @click="hapus=false;general=true">Tidak</button>
                                                                    <button class="btn btn-sm btn-danger" @click="hapus=false;general=true" wire:click="delete({{ $creditPayment->id }})">Ya</button>
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
                                                <i>
                                                    Belum Ada Pembayaran Angsuran
                                                </i>
                                            </td>
                                            
                                        </tr>
                                    @endif
                                    
                                </tbody>
                            </table>
                            {{ $creditPayments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</div>
