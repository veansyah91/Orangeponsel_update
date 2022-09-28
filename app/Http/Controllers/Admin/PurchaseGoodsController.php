<?php

namespace App\Http\Controllers\Admin;

use App\Model\Stock;
use App\Model\Ledger;
use App\Model\Outlet;
use App\Model\Account;
use App\Model\Product;
use App\Model\Category;
use App\Model\OutletUser;
use App\Model\PurchaseGoods;
use Illuminate\Http\Request;
use App\Model\AccountPayable;
use App\Model\PurchaseGoodsDetail;
use App\Model\AccountPayableDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PurchaseGoodsController extends Controller
{
    public function index()
    {
        $outletUser = OutletUser::where('user_id', Auth::user()->id)->first();
        $outlet = Outlet::find($outletUser->outlet_id);

        $cashAccounts = Account::where('outlet_id', $outletUser->outlet_id)
                                ->where(function($query) {
                                    $query->where('code', 'like', '110' . '%')
                                        ->orWhere('code','like', '111' . '%');
                                })
                                ->where('is_active', true)
                                ->get();
        return view('admin.purchase-goods.index', [
            'user' => Auth::user(),
            'outletUser' => $outletUser,
            'cashAccounts' => $cashAccounts,
        ]);
    }

    public function getData()
    {
        $purchaseGoods  = PurchaseGoods::filter(request(['outlet_id','search','date_from','date_to','this_week','this_month','this_year']))
                                    ->orderBy('date', 'desc')
                                    ->orderBy('id', 'desc')
                                    ->get();
        return response()->json([
            'status' => 'success',
            'data' => $purchaseGoods
        ]);
    }

    public function getDataDetail($id)
    {
        $purchaseGoods = PurchaseGoods::find($id);

        $purchaseGoodsDetail = PurchaseGoodsDetail::where('purchase_goods_id', $id)->with('product')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                        'purchaseGoods' => $purchaseGoods,
                        'purchaseGoodsDetail' => $purchaseGoodsDetail,
                      ]
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

        $endPurchaseGoods = PurchaseGoods::where('outlet_id', request('outlet_id'))
                                ->where('invoice_number', 'like', 'PG/' . $first_characters_outlet_name . '/' . $date . '%')
                                ->get()->last();

        $newPurchaseGoods = 'PG/' . $first_characters_outlet_name . '/' . $date . '001';

        if ($endPurchaseGoods) {
            $split_end_invoice = explode('/', $endPurchaseGoods['invoice_number']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $newPurchaseGoods = 'PG/' . $first_characters_outlet_name . '/' . $newNumber;
        }

        return response()->json([
            'status' => 'success',
            'data' => $newPurchaseGoods
        ]);
    }

    public function deleteOtherTable($purchaseGoods)
    {
        // hapus data di buku besar 
        $ledgers = Ledger::where('outlet_id', $purchaseGoods['outlet_id'])->where('no_ref', $purchaseGoods['invoice_number'])->get();
        if (count($ledgers) > 0) {
            foreach ($ledgers as $ledger) {
                $ledger->delete();
            }
        }
        
        //hapus stock berdasarkan detail pembelian barang dagang
        $purchaseGoodsDetails = PurchaseGoodsDetail::where('purchase_goods_id', $purchaseGoods['id'])->get();
        foreach ($purchaseGoodsDetails as $purchaseGoodsDetail) {
            $stock = Stock::where('outlet_id', $purchaseGoods['outlet_id'])->where('product_id', $purchaseGoodsDetail['product_id'])->first();

            $updateStockBalance = $stock['jumlah'] - $purchaseGoodsDetail['qty'];

            $stock->update([
                'jumlah' => $updateStockBalance
            ]);

            $purchaseGoodsDetail->delete();
        }

        if (!$purchaseGoods['cashier_id']) {
            $accountPayable = AccountPayable::where('outlet_id', $purchaseGoods['outlet_id'])
                                                ->where('supplier_id', $purchaseGoods['supplier_id'])
                                                ->first();

            if ($accountPayable) {
                $updateBalance = $accountPayable['balance'] - $purchaseGoods['value'];
                $accountPayable->update([
                    'value' => $updateBalance
                ]);

                // hapus data pada table rincian hutang
                $accountPayableDetails = AccountPayableDetail::where('account_payable_id', $accountPayable['id'])->where('ref', $purchaseGoods['invoice_number'])->first();

                if ($accountPayableDetails) {
                    $accountPayableDetails->delete();
                }
            }
        }
    }

    public function createOtherTable($request, $purchaseGoods)
    {
        foreach ($request->products as $product) {
            $purchaseGoodsDetail = PurchaseGoodsDetail::create([
                'purchase_goods_id' => $purchaseGoods['id'],
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'value' => $product['value'],
                'qty' => $product['qty'],
            ]);

            //tambah stock jika ada, jika belum ada buat baru
            //cek stock
            $stock = Stock::where('outlet_id', $request->outletId)->where('product_id', $product['id'])->first();

            if ($stock) {
                $updateStock = $stock['jumlah'] + $product['qty'];

                $stock->update([
                    'jumlah' => $updateStock
                ]);
            } else {
                $stock = Stock::create([
                    'outlet_id' => $request->outletId,
                    'jumlah' => $product['qty'],
                    'product_id' => $product['id'],
                ]);
            }

            //tambah ke akun persediaan barang
            $p = Product::find($product['id']);
            $category = Category::find($p['category_id']);

            $name_account = 'Persediaan ' . $category['nama'];
            $account = Account::where('outlet_id', $request->outletId)->where('name', $name_account)->first();

            $ledger = Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $request->invoiceNumber,
                'debit' => $product['value'],
                'credit' => 0,
                'date' => date('Y-m-d'),
                'description' => 'Purchase Goods'
            ]);
        }

        if ($request->cashierId > 0) {
            $account = Account::find($request->cashierId);

            $ledger = Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $request->invoiceNumber,
                'debit' => 0,
                'credit' => $request->grandTotal,
                'date' => date('Y-m-d'),
                'description' => 'Purchase Goods'
            ]);
        } 
        else 
        {
            $account = Account::where('outlet_id', $request->outletId)->where('code', '2100000')->first();

            $ledger = Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $request->invoiceNumber,
                'debit' => 0,
                'credit' => $request->grandTotal,
                'date' => date('Y-m-d'),
                'description' => 'Purchase Goods'
            ]);

            //buat data di table hutang dan detail hutang
            //cek apakah pernah berhutang dengan supplier
            $accountPayable = AccountPayable::where('outlet_id', $request->outletId)  
                                        ->where('supplier_id', $request->supplierId)
                                        ->first();

            if ($accountPayable) {
                $newBalance = $accountPayable['balance'] + $request->grandTotal;
                $accountPayable->update([
                    'balance' => $newBalance
                ]);

            } else {
                $accountPayable = AccountPayable::create([
                    'outlet_id' => $request->outletId,
                    'supplier_id' => $request->supplierId,
                    'supplier_name' => $request->supplierName,
                    'balance' => $request->grandTotal

                ]);
            }

            //buat account payable detail
            $accountPayableDetail = AccountPayableDetail::create([
                'account_payable_id' => $accountPayable['id'],
                'debit' => $request->grandTotal,
                'credit' => 0,
                'ref' => $request->invoiceNumber,
                'date' => date('Y-m-d'),
                'due_date' => $request->dueDate ? $request->dueDate : null,
                'is_paid' => false
            ]);
        }
    }

    public function storeData(Request $request)
    {
        $purchaseGoods = PurchaseGoods::create([
            'outlet_id' => $request->outletId,
            'supplier_id' => $request->supplierId,
            'cashier_id' => $request->cashierId > 0 ? $request->cashierId : null,
            'supplier_name' => $request->supplierName,
            'invoice_number' => $request->invoiceNumber,
            'date' => date('Y-m-d'),
            'value' => $request->grandTotal,
        ]);

        $this->createOtherTable($request, $purchaseGoods);
        
        return response()->json([
            'status' => 'success',
            'data' => $purchaseGoods
        ]);
    }

    public function updateData(Request $request, $id)
    {
        $purchaseGoods = PurchaseGoods::find($id);

        $purchaseGoods->update([
            'supplier_id' => $request->supplierId,
            'cashier_id' => $request->cashierId > 0 ? $request->cashierId : null,
            'supplier_name' => $request->supplierName,
            'invoice_number' => $request->invoiceNumber,
            'value' => $request->grandTotal,
        ]);

        $this->deleteOtherTable($purchaseGoods);

        $this->createOtherTable($request, $purchaseGoods);

        return response()->json([
            'status' => 'success',
            'data' => $purchaseGoods
        ]);
    }

    public function deleteData($id)
    {
        $purchaseGoods = PurchaseGoods::find($id);

        $this->deleteOtherTable($purchaseGoods);
        

        $purchaseGoods->delete();

        return response()->json([
            'status' => 'success',
            'data' => $purchaseGoods
        ]);
    }
}