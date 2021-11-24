<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Asset Report</title>
    <style>
        .text-center{
            text-align:center
        }

        .text-right{
            text-align:right
        }

        table{
            width: 100%;
        }

        table, th, td{
            border-collapse: collapse;
            border: 1px solid black;
            font-size: 12px;
        }

        
    </style>
</head>
<body>
    <center>
        <h2>Aset</h2>

        <table>
            <thead>
                <tr class="text-center">
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0
                @endphp
                @if ($assets->isNotEmpty())
                    @foreach ($assets as $asset)
                        <tr class="text-center">
                            <td>{{ $asset->nama }}</td>
                            <td>{{ $asset->jumlah }}</td>
                            <td class="text-right">{{ number_format($asset->harga,0,",",".") }}</td>
                            <td class="text-right">{{ number_format($asset->harga * $asset->jumlah,0,",",".") }}</td>
                            @php
                                $total += $asset->harga * $asset->jumlah
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"> 
                            <strong>Jumlah</strong>                            
                        </td>
                        <td class="text-right">
                            {{ number_format($total,0,",",".") }}
                        </td>
                    </tr>
                @else
                    <tr class="text-center">
                        <td colspan="4">
                            <i>Data Kosong</i>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </center>
</body>
</html>