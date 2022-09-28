<?php

use App\Model\Outlet;
use App\Model\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $outlets = Outlet::all();

        foreach ($outlets as $outlet) {
            // Harta
            // Harta Lancar
            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Kas',
                'code' => '110000',
                'classification' => 'Kas',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Kasir 1',
                'code' => '1100001',
                'classification' => 'Kas',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Bank Mandiri',
                'code' => '1110000',
                'classification' => 'Bank',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Bank BRI',
                'code' => '1110001',
                'classification' => 'Bank',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Bank BNI',
                'code' => '1110002',
                'classification' => 'Bank',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Bank BCA',
                'code' => '1110003',
                'classification' => 'Bank',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Digipos',
                'code' => '1120000',
                'classification' => 'Server',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Payfazz',
                'code' => '1120001',
                'classification' => 'Server',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Gopay',
                'code' => '1130000',
                'classification' => 'Dompet Digital',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'OVO',
                'code' => '1130001',
                'classification' => 'Dompet Digital',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Peralatan', //alat-alat yang akan habis dalam kurang dari 1 tahun
                'code' => '1140000',
                'classification' => 'Peralatan',
                'cash' => true,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Piutang Dagang',
                'code' => '1200000',
                'classification' => 'Piutang Dagang',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Piutang Service',
                'code' => '1200001',
                'classification' => 'Piutang Dagang',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Piutang Lain',
                'code' => '1299000',
                'classification' => 'Piutang Lain',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Persediaan Barang Dagang',
                'code' => '1300000',
                'classification' => 'Persediaan Barang',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Pedapatan Dibayar Dimuka',
                'code' => '1400000',
                'classification' => 'Pendapatan',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Biaya Dibayar Dimuka', //misal beban sewa, asuransi
                'code' => '1500000',
                'classification' => 'Biaya Dibayar Dimuka',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Peralatan', 
                'code' => '1600000',
                'classification' => 'Harta Tetap',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Bangunan', 
                'code' => '1800000',
                'classification' => 'Harta Tetap',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Penyusutan', //misal beban sewa
                'code' => '1900000',
                'classification' => 'Penyusutan',
                'cash' => false,
                'is_active' => true,
            ]);

            //Liability
            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Utang Dagang', //
                'code' => '2100000',
                'classification' => 'Utang Usaha',
                'cash' => false,
                'is_active' => true,
            ]);

            //Equity
            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Modal', //
                'code' => '3100000',
                'classification' => 'Modal',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Laba Ditahan', //
                'code' => '3200001',
                'classification' => 'Modal',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Prive', //
                'code' => '3200000',
                'classification' => 'Modal',
                'cash' => false,
                'is_active' => true,
            ]);

            //Laba Rugi
            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Penjualan Produk', //
                'code' => '4100000',
                'classification' => 'Penjualan Produk',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Retur Penjualan', //
                'code' => '4300000',
                'classification' => 'Retur Penjualan',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Retur Penjualan Lain', //
                'code' => '4399000',
                'classification' => 'Retur Penjualan',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Pendapatan', //
                'code' => '4200000',
                'classification' => 'Pendapatan Usaha',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Pendapatan Service', //
                'code' => '4201000',
                'classification' => 'Pendapatan Usaha',
                'cash' => false,
                'is_active' => true,
            ]);
            
            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Pendapatan Lain', //
                'code' => '4202000',
                'classification' => 'Pendapatan Usaha',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Beban Sewa', //
                'code' => '5100000',
                'classification' => 'Beban Usaha',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Beban Listrik', //
                'code' => '5101000',
                'classification' => 'Beban Usaha',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Beban Gaji', //
                'code' => '5102000',
                'classification' => 'Beban Usaha',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Beban Air', //
                'code' => '5103000',
                'classification' => 'Beban Usaha',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Beban Pengiriman Barang', //
                'code' => '5104000',
                'classification' => 'Beban Usaha',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Harga Pokok Penjualan Barang Dagang', //
                'code' => '5200000',
                'classification' => 'Harga Pokok Penjualan',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Harga Pokok Penjualan Top Up', //
                'code' => '5209999',
                'classification' => 'Harga Pokok Penjualan',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Return Pembelian Barang Dagang', //
                'code' => '5300000',
                'classification' => 'Return Pembelian',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Return Pembelian Barang Dagang Lain', //
                'code' => '5399000',
                'classification' => 'Return Pembelian',
                'cash' => false,
                'is_active' => true,
            ]);

            Account::create([
                'outlet_id' => $outlet->id,
                'name' => 'Iktisar Laba Rugi', //
                'code' => '6990000',
                'classification' => 'Return Pembelian',
                'cash' => false,
                'is_active' => true,
            ]);
        }
        
    }
}
