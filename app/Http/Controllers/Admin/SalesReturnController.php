<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ledger;
use App\Model\Outlet;
use App\Model\Account;
use App\Model\Product;
use App\Model\Category;
use App\Model\OutletUser;
use App\Model\SalesReturn;
use Illuminate\Http\Request;
use App\Model\SalesReturnDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SalesReturnController extends Controller
{
    
    public function index(){
        $outletUser = OutletUser::where('user_id', Auth::user()->id)->first();
        $outlet = Outlet::find($outletUser->outlet_id);

        $cashAccounts = Account::where('outlet_id', $outletUser->outlet_id)
                                ->where(function($query) {
                                    $query->where('code', 'like', '110' . '%')
                                        ->orWhere('code','like', '111' . '%');
                                })
                                ->where('is_active', true)
                                ->get();
        return view('admin.sales-return.index', [
            'user' => Auth::user(),
            'outletUser' => $outletUser,
            'cashAccounts' => $cashAccounts,
        ]);
    }

    public function getData()
    {
        $salesReturn  = SalesReturn::filter(request(['outlet_id','search','date_from','date_to','this_week','this_month','this_year']))
                                    ->orderBy('date', 'desc')
                                    ->orderBy('id', 'desc')
                                    ->get();
        // return json
        return response()->json([
            'status' => 'success',
            'data' => $salesReturn
        ]);
    }

    public function newInvoiceNumber()
    {
        $outlet = Outlet::find(request('outlet_id'));
        $outletSplit = explode(' ', $outlet['nama']);
        $first_characters_outlet_name = '';

        foreach ($outletSplit as $key => $os) {
            $splitOs = str_split($os);
            $first_characters_outlet_name .= $splitOs[0];
        }

        $date = date('Ymd');

        $endSalesReturn = SalesReturn::where('outlet_id', request('outlet_id'))
                                ->where('invoice_number', 'like', 'SR/' . $first_characters_outlet_name . '/' . $date . '%')
                                ->get()->last();

        $newSalesReturn = 'SR/' . $first_characters_outlet_name . '/' . $date . '001';

        if ($endSalesReturn) {
            $split_end_invoice = explode('/', $endSalesReturn['invoice_number']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $newSalesReturn = 'SR/' . $first_characters_outlet_name . '/' . $newNumber;
        }

        return response()->json([
            'status' => 'success',
            'data' => $newSalesReturn
        ]);
    }

    public function storeData()
    {

        $salesReturn = SalesReturn::create([
            'outlet_id' => request('outletId'),
            'customer_id' => request('customerId'),
            'date' => date('Y-m-d'),
            'value' => request('grandTotal'),
            'customer_name' => request('customerName'),
            'invoice_number' => request('invoiceNumber'),
            'cashier_id' => request('cashierId'),
        ]);

        foreach (request('products') as $product) {
            SalesReturnDetail::create([
                'sales_return_id' => $salesReturn['id'],
                'product_name' => $product['name'],
                'qty' => $product['qty'],
                'value' => $product['value'],
            ]);
        }

        // tambahkan ke buku besar
        //akun kas
        $account = Account::find(request('cashierId'));
        Ledger::create([
            'outlet_id' => request('outletId'),
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => request('invoiceNumber'),
            'debit' => 0,
            'credit' => request('grandTotal'),
            'date' => date('Y-m-d'),
            'description' => 'Sales Return'
        ]);

        //laba ditahan
        $account = Account::where('outlet_id', request('outletId'))->where('code','3200001')->first();
        Ledger::create([
            'outlet_id' => request('outletId'),
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => request('invoiceNumber'),
            'debit' => $product['value'],
            'credit' => 0,
            'date' => date('Y-m-d'),
            'description' => 'Sales Return'
        ]);

        //akun Laba Rugi
        // $account_name = 'Harga Pokok Penjualan ' . $category['nama'];
        $account = Account::where('outlet_id', request('outletId'))->where('code', '6990000')->first();

        Ledger::create([
            'outlet_id' => request('outletId'),
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => request('invoiceNumber'),
            'debit' =>  0,
            'credit' => $product['value'],
            'date' => date('Y-m-d'),
            'description' => 'Sales Return'
        ]);

        foreach (request('products') as $product) {
            $productClassified = Product::where('tipe', $product['name'])->first();
            if ($productClassified) {
                //akun return penjualan
                $category = Category::find($productClassified['category_id']);
                $account_name = 'Retur Penjualan ' . $category['nama'];

                $account = Account::where('outlet_id', request('outletId'))->where('name', $account_name)->first();

                Ledger::create([
                    'outlet_id' => request('outletId'),
                    'account_id' => $account['id'],
                    'account' => $account['name'],
                    'no_ref' => request('invoiceNumber'),
                    'debit' => $product['value'],
                    'credit' => 0,
                    'date' => date('Y-m-d'),
                    'description' => 'Sales Return'
                ]);

            } else {
                $account = Account::where('name', 'Retur Penjualan Lain')->first();
                Ledger::create([
                    'outlet_id' => request('outletId'),
                    'account_id' => $account['id'],
                    'account' => $account['name'],
                    'no_ref' => request('invoiceNumber'),
                    'debit' => $product['value'],
                    'credit' => 0,
                    'date' => date('Y-m-d'),
                    'description' => 'Sales Return'
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $salesReturn
        ]);
    }

    public function deleteData($id)
    {
        $salesReturn = SalesReturn::find($id);
        $salesReturn->delete();

        $ledgers = Ledger::where('no_ref', $salesReturn['invoice_number'])->get();

        foreach ($ledgers as $ledger) {
            $ledger->delete();
        }

        $salesReturnDetails = SalesReturnDetail::where('sales_return_id', $id)->get();

        if (count($salesReturnDetails) > 0) {
            foreach ($salesReturnDetails as $salesReturnDetail) {
                $salesReturnDetail->delete();
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $salesReturn
        ]);
    }

    public function updateData()
    {

        $salesReturn = SalesReturn::where('outlet_id', request('outletId'))->where('invoice_number', request('invoiceNumber'))->first();

        $salesReturn->update([
            'customer_id' => request('customerId'),
            'value' => request('grandTotal'),
            'customer_name' => request('customerName'),
            'cashier_id' => request('cashierId'),
        ]);

        //hapus dahulu salesReturnDetail
        $salesReturnDetails = SalesReturnDetail::where('sales_return_id', $salesReturn['id'])->get();

        foreach ($salesReturnDetails as $salesReturnDetail) {
            $salesReturnDetail->delete();
        }

        //buat baru sales return detail
        foreach (request('products') as $product) {
            SalesReturnDetail::create([
                'sales_return_id' => $salesReturn['id'],
                'product_name' => $product['name'],
                'qty' => $product['qty'],
                'value' => $product['value'],
            ]);
        }

        //hapus ledger berdasarkan invoice number
        $ledgers = Ledger::where('outlet_id', request('outletId'))->where('no_ref', request('invoiceNumber'))->get();
        foreach ($ledgers as $ledger) {
            $ledger->delete();
        }

        //buat baru ledger
        //akun kas
        $account = Account::find(request('cashierId'));
        Ledger::create([
            'outlet_id' => request('outletId'),
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => request('invoiceNumber'),
            'debit' => 0,
            'credit' => request('grandTotal'),
            'date' => date('Y-m-d'),
            'description' => 'Sales Return'
        ]);

        //laba ditahan
        $account = Account::where('outlet_id', request('outletId'))->where('code','3200001')->first();
        Ledger::create([
            'outlet_id' => request('outletId'),
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => request('invoiceNumber'),
            'debit' => $product['value'],
            'credit' => 0,
            'date' => date('Y-m-d'),
            'description' => 'Sales Return'
        ]);

        //akun Laba Rugi
        // $account_name = 'Harga Pokok Penjualan ' . $category['nama'];
        $account = Account::where('outlet_id', request('outletId'))->where('code', '6990000')->first();

        Ledger::create([
            'outlet_id' => request('outletId'),
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => request('invoiceNumber'),
            'debit' =>  0,
            'credit' => $product['value'],
            'date' => date('Y-m-d'),
            'description' => 'Sales Return'
        ]);

        foreach (request('products') as $product) {
            $productClassified = Product::where('tipe', $product['name'])->first();
            if ($productClassified) {
                //akun return penjualan
                $category = Category::find($productClassified['category_id']);
                $account_name = 'Retur Penjualan ' . $category['nama'];

                $account = Account::where('outlet_id', request('outletId'))->where('name', $account_name)->first();

                Ledger::create([
                    'outlet_id' => request('outletId'),
                    'account_id' => $account['id'],
                    'account' => $account['name'],
                    'no_ref' => request('invoiceNumber'),
                    'debit' => $product['value'],
                    'credit' => 0,
                    'date' => date('Y-m-d'),
                    'description' => 'Sales Return'
                ]);

            } else {
                $account = Account::where('name', 'Retur Penjualan Lain')->first();
                Ledger::create([
                    'outlet_id' => request('outletId'),
                    'account_id' => $account['id'],
                    'account' => $account['name'],
                    'no_ref' => request('invoiceNumber'),
                    'debit' => $product['value'],
                    'credit' => 0,
                    'date' => date('Y-m-d'),
                    'description' => 'Sales Return'
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $salesReturn
        ]);
    }
}
