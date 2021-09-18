<div>
    <div class="row justify-content-center">
        <div class="col-lg-10 col-12">
            <div class="card">
                <div class="card-header h3">
                    
                    <div class="row justify-content-between">
                        <div class="col">Store Angsuran</div>
                        <div class="col text-right">
                            <button class="btn btn-success" wire:click="backToIndex">
                                kembali
                            </button>
                        </div>
                    </div>
                    
                </div>

                <div class="card-body">
                    <table class="table table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="text-center">Nomor Nota</th>
                                <th class="text-center">Tanggal Bayar</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Nomor HP</th>
                                <th class="text-center">Angsuran Ke-</th>
                                <th class="text-center">Jumlah</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($creditPayments as $creditPayment)
                                <tr>
                                    <td class="text-center">{{ $creditPayment->nomor_pembayaran }}</td>
                                    <td class="text-center">{{ $creditPayment->tanggal_bayar }}</td>
                                    <td class="text-center">{{ CreditPartner::getBiodata($creditPayment->credit_application_id)["nama"] }}</td>
                                    <td class="text-center">{{ CreditPartner::getBiodata($creditPayment->credit_application_id)["no_hp"] }}</td>
                                    <td class="text-center">{{ $creditPayment->angsuran_ke }}</td>
                                    <td class="text-center">Rp. {{ number_format($creditPayment->jumlah,0,",",".") }}</td>
                                    @php
                                        $total += $creditPayment->jumlah;
                                    @endphp
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="5" class="text-right">Jumlah :</th>
                                <th>Rp. {{ number_format($total,0,",",".") }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <form wire:submit.prevent="store">
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <div class="form-group row my-auto">
                                    <label for="collector" class="col-sm-3 col-form-label">Collector</label>
                                    <div class="col-sm-9">
                                        <select class="form-control @error('sales_name') is-invalid @enderror" id="collector" wire:model="sales_name">
                                            <option class="text-center">-- Pilih Collector --</option>
                                            @foreach ($creditSales as $creditSale)
                                                <option value="{{ User::getUser($creditSale->user_id)['name'] }}" >{{ User::getUser($creditSale->user_id)['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 my-auto text-right">
                                <button class="btn btn-primary" type="submit">
                                    Store
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-12">
                            <center class="text-danger font-italic">
                                <small>*harap periksa kembali nomor nota, jumlah dan angsuran ke- berdasarkan NOTA</small>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
