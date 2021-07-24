<div>
    <div x-data="{ bayar: false, detail: false }">
        <div class="row mb-1 text-center">
            <div class="col-sm-3">
                {{ Invoice::getDetail($invoiceId)->no_nota }}
            </div>
            <div class="col-sm-3">
                {{ $customerName }}
            </div>
            <div class="col-sm-3">
                Rp. {{ number_format($sisa,0,",",".") }}
            </div>
            <div class="col-sm-3">
                <button class="btn btn-primary btn-sm" type="button" @click="detail = true">Detail</button>
                <button class="btn btn-sm btn-success" type="button" @click="bayar = true">Bayar</button>
            </div>
        </div>
    
        <div class="row mb-1 container">
            <div x-show="detail" @click.away="detail = false" class="col-sm-12 text-center">
                @if (Invoice::getDebtPaymentDetail($invoiceId)->isNotEmpty())
                    <div class="row">
                        <div class="col-sm-4 font-weight-bold">
                            Jumlah
                        </div>
                        <div class="col-sm-4 font-weight-bold">
                            Tanggal
                        </div>
                        <div class="col-sm-4 font-weight-bold">
                            Aksi
                        </div>
                    </div>
                    @foreach (Invoice::getDebtPaymentDetail($invoiceId) as $item)
                        <div class="row mb-1">
                            <div class="col-sm-4 ">
                                Rp. {{ number_format($item->bayar,0,",",".") }}
                            </div>
                            <div class="col-sm-4 ">
                                {{ $item->updated_at }}
                            </div>
                            <div class="col-sm-4 ">
                                <button class="btn btn-sm btn-danger" wire:click="paymentDelete({{ $item->id }})">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <i class="text-danger text-center">Belum melakukan pembayaran</i> 
                @endif
            </div>
            <div x-show="bayar" @click.away="bayar = false">
                <form class="form-inline" wire:submit.prevent="makePayment">
                    <input type="text" hidden wire:model="invoiceId">
                    <div class="form-group mb-2">
                        <label for="jumlah" class="sr-only ">Jumlah</label>
                        <input type="hidden" class="form-control-plaintext text-right" value="Jumlah">
                        <input type="text" class="form-control-plaintext text-right" value="Jumlah">
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="inputNominal{{ $paymentId }}" class="sr-only">Nominal</label>
                        <input type="number" class="form-control text-right" id="inputNominal{{ $paymentId }}" placeholder="nominal" min="0" wire:model="jumlahBayar">
                    </div>
                    <button type="submit" class="btn btn-info btn-sm mb-2" @click="bayar = false">Proses</button>
                </form>
                
            </div>
        </div>
    </div>
    
    
</div>
