<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>Stock Report</title>
</head>
<body>
    <center>
        <h2>Stok Barang</h2>

        <table class="table table-bordered table-sm mt-2">
            <thead>
                <tr class="text-center">
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Kode</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0
                @endphp
                @if ($stocks->isNotEmpty())
                    @foreach ($stocks as $stock)
                        <tr class="text-center">
                            <td>{{ $stock->category_name }}</td>
                            <td>{{ $stock->tipe }}</td>
                            <td>{{ $stock->kode }}</td>
                            <td>{{ $stock->jumlah }}</td>
                            <td class="text-right">{{ number_format($stock->modal,0,",",".") }}</td>
                            <td class="text-right">{{ number_format($stock->modal * $stock->jumlah,0,",",".") }}</td>
                            @php
                                $total += $stock->modal * $stock->jumlah
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-right"> 
                            <strong>Jumlah</strong>                            
                        </td>
                        <td>
                            {{ number_format($total,0,",",".") }}
                        </td>
                    </tr>
                @else
                    <tr class="text-center">
                        <td colspan="5">
                            <i>Data Kosong</i>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </center>
</body>
</html>