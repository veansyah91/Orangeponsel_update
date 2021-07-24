<div>
    <div class="card">
        <div class="card-header h4">
            
            <div class="row justify-content-between">
                <div class="col-5">
                    Detail Invoice
                </div>
                <div class="col-5 text-right">
                    <button class="btn btn-sm btn-success" wire:click="backTo()">Kembali</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr class="text-center">
                        <th scope="col">Produk</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td class="text-center">{{ $d->product->tipe }}</td>
                                <td class="text-center">{{ $d->jumlah }}</td>
                                <td class="text-center">Rp. {{ number_format($d->jual,0,",",".") }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
