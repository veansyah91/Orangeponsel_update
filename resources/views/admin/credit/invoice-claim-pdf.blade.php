<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <style>
    </style>

    <title>Invoice Kredit</title>
</head>
<body>

    <table style="width: 100%">
        <tr>
            <td style="width:40%">
                <div class="outlet-name" style="font-size: 16px">
                    <strong>ORANGE PONSEL Group</strong> 
                </div>
            
                <div class="address" style="font-size: 12px">
                    Air Molek, Kec. Pasir Penyu, Kab. INHU
                </div>
                <div class="address" style="font-size: 12px;font-weight:bold">
                    0812 7681 1842 (Ferdi Yansyah)
                </div>
            </td>
            <td style="width:10%;font-size: 12px; vertical-align: text-top; text-align:right">
                Kepada:
            </td>
            <td style="width:30%">
                <div class="outlet-name" style="font-size: 16px">
                    <strong>BKS</strong> 
                </div>
            
                <div class="address" style="font-size: 12px">
                    Berkah Keluarga Sejahtera 
                </div>
                <div class="address" style="font-size: 12px">
                    Pangkalan Kerinci
                </div>
            </td>
        </tr>
    </table>

    <center style="margin-top: 30px">
        <h3>INVOICE</h3>        
    </center>

    <table style="width: 100%">
        <tr>
            <td>
                <div style="font-size: 12px">
                    Nomor: Orange Ponsel-{{ $creditPartner['alias'] }}/{{ $lastInvoice }}
                </div>
            </td>
            <td>
                <div style="font-size: 12px;text-align:right">
                    Tanggal: {{ Date('Y-m-d') }}
                </div>
            </td>
        </tr>
    </table>

    <table 
        style="width: 100%;border-collapse: collapse;border: 1px solid black;"

        class="app-table mt-1"
    >
        <thead style="font-size: 12px;border: 1px solid black;">
            <tr>
                <th class="text-center" style="font-size: 12px;border: 1px solid black;">No</th>
                <th class="text-center" style="font-size: 12px;border: 1px solid black;">Tanggal</th>
                <th class="text-center" style="font-size: 12px;border: 1px solid black;">Nama Konsumen</th>
                <th class="text-center" style="font-size: 12px;border: 1px solid black;">No HP</th>
                <th class="text-center" style="font-size: 12px;border: 1px solid black;">Kode / Imei</th>
                <th class="text-center" style="font-size: 12px;border: 1px solid black;">Tipe HP</th>
                <th class="text-center" style="font-size: 12px;border: 1px solid black;">Harga</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px;border: 1px solid black;">
            @php
                $i = 1;
                $total = 0;
            @endphp
            @foreach ($invoices as $invoice)
                <tr style="font-size: 12px;border: 1px solid black;">
                    @php
                        $date = explode(' ',$invoice->created_at);
                        $newDate = explode('-',$date[0]);
                    @endphp
                    <td class="text-center" style="font-size: 12px;border: 1px solid black;">{{ $i++ }}</td>
                    <td class="text-center" style="font-size: 12px;border: 1px solid black;">{{ $newDate[2] }}-{{ $newDate[1] }}-{{ $newDate[0] }}</td>
                    <td class="text-center" style="font-size: 12px;border: 1px solid black;">{{ $invoice->nama }}</td>
                    <td class="text-center" style="font-size: 12px;border: 1px solid black;">{{ $invoice->no_hp }}</td>
                    <td class="text-center" style="font-size: 12px;border: 1px solid black;">{{ Product::show($invoice->product_id)->kode }}</td>
                    <td class="text-center" style="font-size: 12px;border: 1px solid black;">{{ Product::show($invoice->product_id)->tipe }}</td>
                    <td class="text-center" style="font-size: 12px;border: 1px solid black;">Rp. {{ number_format(Product::show($invoice->product_id)->jual,0,",",".") }}</td>
                    @php
                        $total += Product::show($invoice->product_id)->jual ;
                    @endphp
                </tr>
            @endforeach

            <tr>
                <td colspan="6" class="text-right" style="font-weight:bold ;font-size: 16px;border: 1px solid black;">Total: </td>
                <td  style="text-align:center;font-weight:bold ;font-size: 16px;border: 1px solid black;">Rp. {{ number_format($total,0,",",".") }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 10px">
        Bank Transfer a.n Ferdi Yansyah:
    </div>
    <div>
        BRI 216001010680503
    </div>
</body>
</html>