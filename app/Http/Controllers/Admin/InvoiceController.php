<?php

namespace App\Http\Controllers\Admin;

use App\Model\Stock;
use App\Model\Ledger;
use App\Model\Outlet;
use App\Model\Account;
use App\Model\Invoice;
use App\Model\Product;
use App\Model\Category;
use App\Model\Customer;
use App\Model\OutletUser;
use App\Model\TopupInvoice;
use App\Model\InvoiceDetail;
use App\Model\OutletInvoice;
use Illuminate\Http\Request;
use App\Model\AccountReceivable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\AccountReceivableDetail;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $outletUser = OutletUser::where('user_id', $user->id)
                                ->first();

        $cashAccounts = Account::where('outlet_id', $outletUser->outlet_id)
                                ->where(function($query) {
                                    $query->where('code', 'like', '110' . '%')
                                        ->orWhere('code','like', '111' . '%');
                                })
                                ->where('is_active', true)
                                ->get();

        return view('admin.daily.invoice.index', [
            'outlets' => Outlet::all(),
            'outlet' => Outlet::find($outletUser->outlet_id),
            'outlet_id' => $outletUser->outlet_id,
            'cashAccounts' => $cashAccounts
        ]);
    }

    public function getInvoiceNumber(Request $request){
        $user = Auth::user();

        $outlet = Outlet::find(request('outlet_id'));
        $outletSplit = explode(' ', $outlet['nama']);
        $first_characters_outlet_name = '';

        foreach ($outletSplit as $key => $os) {
            $splitOs = str_split($os);
            $first_characters_outlet_name .= $splitOs[0];
        }

        $date = date('Ymd');

        $endInvoice = Invoice::where('outlet_id', $request->outlet_id)
                                ->where('no_nota', 'like', 'INV/' . $first_characters_outlet_name . '/' . $date . '%')
                                ->get()->last();

        $newInvoice = 'INV/' . $first_characters_outlet_name . '/' . $date . '001';

        if ($endInvoice) {
            $split_end_invoice = explode('/', $endInvoice['no_nota']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $newInvoice = 'INV/' . $first_characters_outlet_name . '/' . $newNumber;
        }

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $newInvoice
        ]);
    }

    public function create(Request $request)
    {
        $newInvoice = $request->nomor_nota;

        // cek apakah invoice sudah ada atau belum
        $outlet = Outlet::find($request->outletId);
        $outletSplit = explode(' ', $outlet['nama']);
        $first_characters_outlet_name = '';

        foreach ($outletSplit as $key => $os) {
            $splitOs = str_split($os);
            $first_characters_outlet_name .= $splitOs[0];
        }

        $date = date('Ymd');

        $invoice = Invoice::where('outlet_id', $request->outletId)->where('no_nota',$request->nomor_nota)->get()->last();

        if($invoice){
            $split_end_invoice = explode('/', $invoice['no_nota']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $newInvoice = 'INV/' . $first_characters_outlet_name . '/' . $newNumber;
        }

        // create new invoice
        $account = Account::find($request->cashierId);

        $invoice = Invoice::create([
            'outlet_id' => $request->outletId,
            'customer_id' => $request->pelangganId,
            'no_nota' => $newInvoice,
            'account_name' => $account ? $account['name'] : 'Piutang Dagang',
            'cashier_id' => $account ? $account['id'] : 0,
        ]);

        $cogs = 0;
        $revenue = 0;

        // create new invoice detail
        $total = 0;
        foreach ($request->detail as $detail) {
            $invoiceDetail = InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'product_id' => $detail['idProduk'],
                'jual' => $detail['harga'],
                'jumlah' => $detail['jumlah'],
            ]);

            // reduce stock
            $stock = Stock::where('product_id', $detail['idProduk'])
                            ->where('outlet_id', $request->outletId)
                            ->first();

            $stock->jumlah = $stock->jumlah - $detail['jumlah'];

            $stock->save();

            $total += $detail['harga'] * $detail['jumlah'];

            //isi buku besar penjualan produk
            $product = Product::find($detail['idProduk']);
            $category = Category::find($product['category_id']);

            //akun penjualan produk
            $account_name = 'Penjualan ' . $category['nama'];
            $account = Account::where('outlet_id', $request->outletId)->where('name', $account_name)->first();
            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $newInvoice,
                'debit' => 0,
                'credit' => $detail['harga'] * $detail['jumlah'],
                'date' => date('Y-m-d'),
                'description' => 'Invoice'
            ]);

            $revenue += $detail['harga'] * $detail['jumlah'];
            
            //akun persediaan
            $account_name = 'Persediaan ' . $category['nama'];
            $account = Account::where('outlet_id', $request->outletId)->where('name', $account_name)->first();
            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $newInvoice,
                'debit' => 0,
                'credit' => $product['modal'] * $detail['jumlah'],
                'date' => date('Y-m-d'),
                'description' => 'Invoice'
            ]);

            //akun HPP
            $account_name = 'Harga Pokok Penjualan ' . $category['nama'];
            $account = Account::where('outlet_id', $request->outletId)->where('name', $account_name)->first();
            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $newInvoice,
                'debit' => $product['modal'] * $detail['jumlah'],
                'credit' => 0,
                'date' => date('Y-m-d'),
                'description' => 'Invoice'
            ]);

            $cogs += $product['modal'] * $detail['jumlah'];

        }

        //masukkan ke akun kas
        if ($request->totalBayar > 0) {
            $account = Account::find($request->cashierId);
            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $newInvoice,
                'debit' => $request->totalBayar < $total ? $total - $request->totalBayar : $total ,
                'credit' => 0,
                'date' => date('Y-m-d'),
                'description' => 'Invoice'
            ]);
        }

        //akun laba ditahan
        $account = Account::where('outlet_id', $request->outletId)->where('code','3200001')->first();
        Ledger::create([
            'outlet_id' => $request->outletId,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $newInvoice,
            'debit' => $revenue - $cogs < 0 ? $cogs - $revenue : 0,
            'credit' => $revenue - $cogs > 0 ? $revenue - $cogs : 0,
            'date' => date('Y-m-d'),
            'description' => 'Invoice'
        ]);

        //akun ikhtisar laba rugi
        $account = Account::where('outlet_id', $request->outletId)->where('code','6990000')->first();
        Ledger::create([
            'outlet_id' => $request->outletId,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $newInvoice,
            'debit' => $revenue - $cogs > 0 ? $revenue - $cogs : 0,
            'credit' => $revenue - $cogs < 0 ? $cogs - $revenue : 0,
            'date' => date('Y-m-d'),
            'description' => 'Invoice'
        ]);

        // isi buku besar kas atau piutang

        if ($request->totalBayar < $total) {
            $account = Account::where('outlet_id', $request->outletId)->where('code','1200000')->first();
            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $newInvoice,
                'debit' => $total - $request->totalBayar,
                'date' => date('Y-m-d'),
                'description' => 'General Invoice'
            ]);

            $customer = Customer::find($request->pelangganId);

            //cek apakah pernah punya piutang atas customer sebelumnya
            $accountReceivable = AccountReceivable::where('outlet_id', $request->outletId)->where('customer_id', $customer['id'])->first();

            if ($accountReceivable) {
                $old_balance = $accountReceivable['balance'];
                $accountReceivable->update([
                    'balance' => $old_balance + ($total - $request->totalBayar)
                ]);
            } else {
                $accountReceivable = AccountReceivable::create([
                    'outlet_id' => $request->outletId,
                    'customer_id' => $customer['id'],
                    'customer_name' => $customer['nama'],
                    'balance' => $total - $request->totalBayar
                ]);
            }

            //tambah detail account receivable
            $accountReceivableDetail = AccountReceivableDetail::create([
                'account_receivable_id' => $accountReceivable['id'],
                'debit' => $total - $request->totalBayar,
                'ref' => $newInvoice,
                'date' => date('Y-m-d'),
            ]);
        }

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    public function getInvoice(Request $request)
    {
        $invoice = Invoice::where('outlet_id', $request->outletId)
                            ->whereDate('created_at', $request->date)
                            ->with('customer')
                            ->with('invoiceDetail','invoiceDetail.product')
                            ->get();
        
        // return json
        return response()->json([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    public function balance()
    {
        $user = Auth::user();
        
        $outletUser = OutletUser::where('user_id', $user->id)
                                ->first();

        $cashAccounts = Account::where('outlet_id', $outletUser->outlet_id)
                                ->where(function($query) {
                                    $query->where('code', 'like', '110' . '%')
                                        ->orWhere('code','like', '111' . '%');
                                })
                                ->where('is_active', true)
                                ->get();

        $servers = Account::where('outlet_id', $outletUser->outlet_id)
                            ->where('code', 'like', '112' . '%')
                            ->where('is_active', true)
                            ->get();

        return view('admin.daily.invoice.balance', [
            'outlets' => Outlet::all(),
            'outlet' => Outlet::find($outletUser->outlet_id),
            'outlet_id' => $outletUser->outlet_id,
            'cashAccounts' => $cashAccounts,
            'servers' => $servers
        ]);
    }

    public function getNewInvoiceNumber()
    {
        $outlet = Outlet::find(request('outlet_id'));
        $outletSplit = explode(' ', $outlet['nama']);
        $first_characters_outlet_name = [];

        foreach ($outletSplit as $key => $os) {
            $splitOs = str_split($os);
            $first_characters_outlet_name[$key] = $splitOs[0];
        }

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $first_characters_outlet_name
        ]);
    }

    public function getTopUpInvoiceNumber()
    {
        $user = Auth::user();

        $outlet = Outlet::find(request('outlet_id'));
        $outletSplit = explode(' ', $outlet['nama']);
        $first_characters_outlet_name = '';

        foreach ($outletSplit as $key => $os) {
            $splitOs = str_split($os);
            $first_characters_outlet_name .= $splitOs[0];
        }

        $date = date('Ymd');

        $endInvoice = TopupInvoice::where('outlet_id', request('outlet_id'))
                                ->where('invoice_number', 'like', 'TOPUP/' . $first_characters_outlet_name . '/' . $date . '%')
                                ->get()->last();

        $newInvoice = 'TOPUP/' . $first_characters_outlet_name . '/' . $date . '001';

        if ($endInvoice) {
            $split_end_invoice = explode('/', $endInvoice['invoice_number']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $newInvoice = 'TOPUP/' . $first_characters_outlet_name . '/' . $newNumber;
        }

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $newInvoice
        ]);
    }

    public function createTopUpInvoice(Request $request)
    {
        $invoiceNumber = $request->invoice_number;

        $outlet = Outlet::find($request->outlet_id);
        $outletSplit = explode(' ', $outlet['nama']);
        $first_characters_outlet_name = '';

        foreach ($outletSplit as $key => $os) {
            $splitOs = str_split($os);
            $first_characters_outlet_name .= $splitOs[0];
        }

        $date = date('Ymd');

        //cek apakah invoice sudah ada
        $invoice = TopupInvoice::where('outlet_id', $request->outlet_id)
                                ->where('invoice_number', $invoiceNumber)
                                ->first();
                                
        if ($invoice) {
            $split_end_invoice = explode('/', $invoice['invoice_number']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $invoiceNumber = 'TOPUP/' . $first_characters_outlet_name . '/' . $newNumber;
        }

        $account = Account::find($request->serverId);

        $customer = Customer::find($request->customerId);

        $invoice = TopupInvoice::create([
            'customer_id' => $customer['id'],
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'product' => $request->product,
            'address_no' => $request->address_no,
            'selling_price' => $request->selling_price,
            'unit_cost' => $request->unit_cost,
            'server' => $account['name'],
            'invoice_number' => $invoiceNumber,
            'cashier_id' => $request->cashierId
        ]);

        //input ledger
        //akun server
        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => date('Y-m-d'),
            'credit' => $request->unit_cost
        ]);

        if ($request->isPaid) {
            $account = Account::find($request->cashierId);

            //akun cash
            Ledger::create([
                'outlet_id' => $request->outlet_id,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $invoiceNumber,
                'description' => 'Top Up Invoice',
                'date' => date('Y-m-d'),
                'debit' => $request->selling_price
            ]);
        } else {
            $account = Account::where('outlet_id', $request->outlet_id)
                                ->where('code', '1200000')
                                ->first();

            Ledger::create([
                'outlet_id' => $request->outlet_id,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $invoiceNumber,
                'description' => 'Top Up Invoice',
                'date' => date('Y-m-d'),
                'debit' => $request->selling_price
            ]);

            $accountReceivable = AccountReceivable::where('outlet_id', $request->outlet_id)->where('customer_id', $customer['id'])
                                                    ->first();

            if ($accountReceivable) {
                $old_balance = $accountReceivable['balance'];
                $accountReceivable->update([
                    'balance' => $old_balance + $request->selling_price
                ]);
            } else {
                $accountReceivable = AccountReceivable::create([
                    'outlet_id' => $request->outlet_id,
                    'customer_id' => $customer['id'],
                    'customer_name' => $customer['nama'],
                    'balance' => $request->selling_price
                ]);
            }

            $accountReceivableDetail = AccountReceivableDetail::create([
                'account_receivable_id' => $accountReceivable['id'],
                'debit' => $request->selling_price,
                'ref' => $invoiceNumber,
                'date' => date('Y-m-d'),
            ]);
        }

        //akun pendapatan
        $account = Account::where('outlet_id', $request->outlet_id)
                            ->where('code', '4200000')
                            ->first();

        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => date('Y-m-d'),
            'credit' => $request->selling_price
        ]);

        //akun HPP
        $account = Account::where('outlet_id', $request->outlet_id)
                            ->where('code', '5209999')
                            ->first();

        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => date('Y-m-d'),
            'debit' => $request->unit_cost
        ]);

        //akun laba rugi
        $account = Account::where('outlet_id', $request->outlet_id)
                            ->where('code', '6990000')
                            ->first();

        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => date('Y-m-d'),
            'debit' => $request->selling_price - $request->unit_cost
        ]);

        //akun laba ditahan
        $account = Account::where('outlet_id', $request->outlet_id)
                            ->where('code', '3200001')
                            ->first();

        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => date('Y-m-d'),
            'credit' => $request->selling_price - $request->unit_cost
        ]);

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    public function updateTopUpInvoice(Request $request)
    {
        // find invoice 
        $invoice = TopupInvoice::where('invoice_number', $request->invoice_number)->first();

        $account = Account::find($request->serverId);

        $customer = Customer::find($request->customerId);

        $invoice->update([
            'customer_id' => $customer['id'],
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'product' => $request->product,
            'address_no' => $request->address_no,
            'selling_price' => $request->selling_price,
            'unit_cost' => $request->unit_cost,
            'server' => $account['name'],
            'cashier_id' => $request->cashierId
        ]);

        $invoiceNumber = $request->invoice_number;

        // hapus dahulu ledger
        $ledgers = Ledger::where('outlet_id', $request->outlet_id)
                        ->where('no_ref', $invoiceNumber)
                        ->get();

        $date = $ledgers[0]['date'];

        foreach ($ledgers as $ledger) {
            $ledger->delete();
        }

        //hapus piutang jika ada
        $accountReceivableDetail = AccountReceivableDetail::where('ref', $invoiceNumber)->first();

        if ($accountReceivableDetail) {
            $accountReceivable = AccountReceivable::find($accountReceivableDetail['account_receivable_id']);
            
            $oldAccountReceivable = $accountReceivable['balance'];
    
            $accountReceivable->update([
                'balance' => $oldAccountReceivable - $accountReceivableDetail['debit']
            ]);
    
            $accountReceivableDetail->delete();
            
        }

        $account = Account::find($request->serverId);

        //buat ledger baru
        //akun server
        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => $date,
            'credit' => $request->unit_cost
        ]);

        if ($request->isPaid) {
            $account = Account::find($request->cashierId);

            //akun cash
            Ledger::create([
                'outlet_id' => $request->outlet_id,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $invoiceNumber,
                'description' => 'Top Up Invoice',
                'date' => date('Y-m-d'),
                'debit' => $request->selling_price
            ]);
        } else {
            $account = Account::where('outlet_id', $request->outlet_id)
                                ->where('code', '1200000')
                                ->first();

            Ledger::create([
                'outlet_id' => $request->outlet_id,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $invoiceNumber,
                'description' => 'Top Up Invoice',
                'date' => date('Y-m-d'),
                'debit' => $request->selling_price
            ]);

            $accountReceivable = AccountReceivable::where('customer_id', $customer['id'])
                                                    ->first();

            if ($accountReceivable) {
                $old_balance = $accountReceivable['balance'];
                $accountReceivable->update([
                    'balance' => $old_balance + $request->selling_price
                ]);
            } else {
                $accountReceivable = AccountReceivable::create([
                    'outlet_id' => $request->outlet_id,
                    'customer_id' => $customer['id'],
                    'customer_name' => $customer['nama'],
                    'balance' => $request->selling_price
                ]);
            }

            $accountReceivableDetail = AccountReceivableDetail::create([
                'account_receivable_id' => $accountReceivable['id'],
                'debit' => $request->selling_price,
                'ref' => $invoiceNumber,
                'date' => date('Y-m-d'),
            ]);
        }

        //akun pendapatan
        $account = Account::where('outlet_id', $request->outlet_id)
                            ->where('code', '4200000')
                            ->first();

        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => $date,
            'credit' => $request->selling_price
        ]);


        //akun HPP
        $account = Account::where('outlet_id', $request->outlet_id)
                            ->where('code', '5209999')
                            ->first();

        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => $date,
            'debit' => $request->unit_cost
        ]);

        //akun laba rugi
        $account = Account::where('outlet_id', $request->outlet_id)
                            ->where('code', '6990000')
                            ->first();

        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => $date,
            'debit' => $request->selling_price - $request->unit_cost
        ]);

        //akun laba ditahan
        $account = Account::where('outlet_id', $request->outlet_id)
                            ->where('code', '3200001')
                            ->first();

        Ledger::create([
            'outlet_id' => $request->outlet_id,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $invoiceNumber,
            'description' => 'Top Up Invoice',
            'date' => $date,
            'credit' => $request->selling_price - $request->unit_cost
        ]);

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    public function deleteTopUpInvoice(TopupInvoice $invoice)
    {
        // hapus dahulu ledger
        $ledgers = Ledger::where('outlet_id', $invoice['outlet_id'])
                        ->where('no_ref', $invoice['invoice_number'])
                        ->get();

        foreach ($ledgers as $ledger) {
            $ledger->delete();
        }

        $invoice->delete();

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    public function getTopUpInvoice(){
        $invoice = TopupInvoice::where('outlet_id', request('outlet_id'))
                                ->whereDate('created_at', request('date'))
                                ->get();

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    public function getTopUpInvoiceDetail(TopupInvoice $invoice)
    {
        $customer = Customer::find($invoice['customer_id']);
        $accountReceivableDetails = AccountReceivableDetail::where('ref', $invoice['invoice_number'])->get();

        // return json
        return response()->json([
            'status' => 'success',
            'data' => [
                        'invoice' => $invoice,
                        'customer' => $customer,
                        'isPaid' => sizeof($accountReceivableDetails) > 0 ? false : true
                      ]
        ]);
    }
}
