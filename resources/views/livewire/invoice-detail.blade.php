<div>
    <h4>Detail Nota</h4>
    <table class="table table-sm table-responsive table-borderless">
        <tbody>
            <tr>
                <td>
                    <strong>Pelanggan</strong>                    
                </td>
                <td colspan="2">: {{ $customer }}</td>
                <td></td>
            </tr>
            @if ($data)
                @php
                    $jumlah = 0;
                    $i = 1;
                @endphp
                @foreach ($data as $detail)
                    <tr>
                        <td colspan="2" class="font-weight-bold">Item {{ $i++ }}</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td>Kode</td>
                        <td>: {{ $detail->kode }}</td>
                    </tr>
                    <tr>
                        <td>Tipe</td>
                        <td>: {{ $detail->tipe }}</td>
                    </tr>
                    <tr>
                        <td>Jual</td>
                        <td>: Rp. {{ number_format($detail->jual,0,",",".") }} x {{ $detail->jumlah }}</td>
                        @php
                            $jumlah += $detail->jual * $detail->jumlah
                        @endphp
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Total</td>
                        <td class="font-weight-bold">: Rp. {{ number_format($jumlah,0,",",".") }}</td>

                    </tr>
                @endforeach    
            @else
                <div wire:loading class="text-center">
                    <i>Processing Detail Invoice...</i>                    
                </div>
            @endif
            
            
        </tbody>
    </table>
</div>
