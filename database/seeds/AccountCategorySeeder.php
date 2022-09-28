<?php

use App\Model\AccountCategory;
use Illuminate\Database\Seeder;

class AccountCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountCategory::create([
            'name' => 'Kas',
        ]);

        AccountCategory::create([
            'name' => 'Bank',
        ]);

        AccountCategory::create([
            'name' => 'Server',
        ]);

        AccountCategory::create([
            'name' => 'Dompet Digital',
        ]);

        AccountCategory::create([
            'name' => 'Piutang Dagang',
        ]);

        AccountCategory::create([
            'name' => 'Piutang Lain',
        ]);

        AccountCategory::create([
            'name' => 'Persediaan Barang',
        ]);

        AccountCategory::create([
            'name' => 'Pendapatan',
        ]);

        AccountCategory::create([
            'name' => 'Biaya Dibayar Dimuka',
        ]);

        AccountCategory::create([
            'name' => 'Harta Tetap',
        ]);

        AccountCategory::create([
            'name' => 'Penyusutan',
        ]);

        AccountCategory::create([
            'name' => 'Utang Usaha',
        ]);

        AccountCategory::create([
            'name' => 'Modal',
        ]);

        AccountCategory::create([
            'name' => 'Pendapatan Usaha',
        ]);

        AccountCategory::create([
            'name' => 'Retur Penjualan',
        ]);

        AccountCategory::create([
            'name' => 'Beban Usaha',
        ]);

        AccountCategory::create([
            'name' => 'Peralatan',
        ]);

        AccountCategory::create([
            'name' => 'Harga Pokok Penjualan',
        ]);

        AccountCategory::create([
            'name' => 'Retur Pembelian',
        ]);
    }
}
