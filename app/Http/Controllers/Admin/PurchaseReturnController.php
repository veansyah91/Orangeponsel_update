<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ledger;
use App\Model\Outlet;
use App\Model\Account;
use App\Model\Product;
use App\Model\Category;
use App\Model\OutletUser;
use Illuminate\Http\Request;
use App\Model\PurchaseReturn;
use App\Model\PurchaseReturnDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PurchaseReturnController extends Controller
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
        return view('admin.purchase-return.index', [
            'user' => Auth::user(),
            'outletUser' => $outletUser,
            'cashAccounts' => $cashAccounts,
        ]);
    }
    
    public function getData()
    {
        $purchaseReturn = PurchaseReturn::filter(request(['outlet_id','search', 'date_from', 'date_to', 'this_week', 'end_week', 'this_month', 'end_month', 'this_year', 'end_year']))
                                        ->orderBy('date_delivery', 'desc')
                                        ->orderBy('id', 'desc')
                                        ->get();
        return response()->json([
            'status' => 'success',
            'data' => $purchaseReturn
        ]);
    }

    public function getSingleData($id)
    {   
        $purchaseReturn = PurchaseReturn::where('id',$id)->with('purchaseReturnDetails')->first();
        return response()->json([
            'status' => 'success',
            'data' => $purchaseReturn
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

        $endPurchaseReturn = PurchaseReturn::where('outlet_id', request('outlet_id'))
                                ->where('no_ref', 'like', 'PR/' . $first_characters_outlet_name . '/' . $date . '%')
                                ->get()->last();

        $newPurchaseReturn = 'PR/' . $first_characters_outlet_name . '/' . $date . '001';

        if ($endPurchaseReturn) {
            $split_end_invoice = explode('/', $endPurchaseReturn['no_ref']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $newPurchaseReturn = 'PR/' . $first_characters_outlet_name . '/' . $newNumber;
        }

        return response()->json([
            'status' => 'success',
            'data' => $newPurchaseReturn
        ]);
    }

    public function createOtherTable($request, $purchaseReturn)
    {
        //create purchase return detail
        foreach ($request->products as $product) {
            PurchaseReturnDetail::create([
                'purchase_return_id' => $purchaseReturn['id'],
                'product_name' => $product['name'],
                'product_id' => $product['id'] ? $product['id'] : null,
                'qty' => $product['qty'],
                'value' => $product['value'],
                'value_approvement' => $product['valueApprovement'],
            ]);

            //tambah ke akun return pembelian barang
            if ($request->cashierId > 0) {

                $account = Account::where('outlet_id', $request->outletId)->where('code', '5399000')->first();

                //cek kategori produk
                $productSelected = Product::find($product['id']);

                if ($productSelected) {
                    $category = Category::find($productSelected['category_id']);
                    
                    $name_account = 'Return Pembelian ' . $category['nama'];

                    $account = Account::where('outlet_id', $request->outletId)->where('name', $name_account)->first();
                              
                } 
                Ledger::create([
                    'outlet_id' => $request->outletId,
                    'account_id' => $account['id'],
                    'account' => $account['name'],
                    'no_ref' => $request->noRef,
                    'debit' => 0,
                    'credit' => $product['valueApprovement'],
                    'date' => $request->dateDelivery ? $request->dateDelivery : $request->dateReceipt,
                    'description' => 'Purchase Return'
                ]); 
                     
            }   
        }

        //tambah ke akun kas
        if ($request->cashierId > 0) {
            //akun kas
            $account = Account::find($request->cashierId);

            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $request->noRef,
                'debit' => $request->valueApprovement,
                'credit' => 0,
                'date' => $request->dateDelivery ? $request->dateDelivery : $request->dateReceipt,
                'description' => 'Purchase Return'
            ]);

            //laba ditahan
            $account = Account::where('outlet_id', request('outletId'))->where('code','3200001')->first();
            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $request->noRef,
                'debit' => $request->valueApprovement,
                'credit' => 0,
                'date' => $request->dateDelivery ? $request->dateDelivery : $request->dateReceipt,
                'description' => 'Purchase Return'
            ]);

            //laba rugi
            $account = Account::where('outlet_id', request('outletId'))->where('code', '6990000')->first();

            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $request->noRef,
                'debit' => 0,
                'credit' => $request->valueApprovement,
                'date' => $request->dateDelivery ? $request->dateDelivery : $request->dateReceipt,
                'description' => 'Purchase Return'
            ]);
        }   
    }

    public function deleteOtherTable($purchaseReturn)
    {
        // hapus table purchase return detail
        $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_return_id', $purchaseReturn['id'])->get();

        if (count($purchaseReturnDetails) > 0) {
            foreach ($purchaseReturnDetails as $purchaseReturnDetail) {
                $purchaseReturnDetail->delete();
            }
        }
        
        //cek buku besar berdasarkan no ref
        $ledgers = Ledger::where('outlet_id', $purchaseReturn['outlet_id'])->where('no_ref', $purchaseReturn['no_ref'])->get();
        
        if (count($ledgers) > 0) {
            foreach ($ledgers as $ledger) {
                $ledger->delete();
            }
        }
    }

    public function storeData(Request $request)
    {
        $account = Account::find($request->cashierId);
        //buat table purchase return
        $purchaseReturn = PurchaseReturn::create([
            'outlet_id' => $request->outletId,
            'supplier_id' => $request->supplierId,
            'supplier_name' => $request->supplierName,
            'date_delivery' => $request->dateDelivery,
            'date_accepted_on_supplier' => $request->dateAcceptedOnSupplier ? $request->dateAcceptedOnSupplier : null,
            'date_receipt' => $request->dateReceipt ? $request->dateReceipt : null,
            'value' => $request->value,
            'value_approvement' => $request->valueApprovement,
            'approvement' => $request->approvementDescription == 'menunggu' ? false : true,
            'approvement_description' => $request->approvementDescription,
            'no_ref' => $request->noRef,
            'account_id' => $request->cashierId > 0 ? $request->cashierId : null,
            'account_name' => $request->cashierId > 0 ?  $account['name'] : null,
        ]);

        //buat table lain
        $this->createOtherTable($request, $purchaseReturn);

        return response()->json([
            'status' => 'success',
            'data' => $purchaseReturn
        ]);
    }

    public function updateData(Request $request, $id)
    {
        $purchaseReturn = PurchaseReturn::find($id);
        $account = Account::find($request->cashierId);
        $purchaseReturn->update([
            'outlet_id' => $request->outletId,
            'supplier_id' => $request->supplierId,
            'supplier_name' => $request->supplierName,
            'date_delivery' => $request->dateDelivery,
            'date_accepted_on_supplier' => $request->dateAcceptedOnSupplier ? $request->dateAcceptedOnSupplier : null,
            'date_receipt' => $request->dateReceipt ? $request->dateReceipt : null,
            'value' => $request->value,
            'value_approvement' => $request->valueApprovement,
            'approvement' => $request->approvementDescription == 'menunggu' ? false : true,
            'approvement_description' => $request->approvementDescription,
            'no_ref' => $request->noRef,
            'account_id' => $request->cashierId > 0 ? $request->cashierId : null,
            'account_name' => $request->cashierId > 0 ?  $account['name'] : null,
        ]);

        //delete table lain
        $this->deleteOtherTable($purchaseReturn);

        //buat table lain
        $this->createOtherTable($request, $purchaseReturn);

        return response()->json([
            'status' => 'success',
            'data' => $purchaseReturn
        ]);
    }

    public function deleteData($id)
    {
        $purchaseReturn = PurchaseReturn::find($id);

        //delete table lain
        $this->deleteOtherTable($purchaseReturn);

        $purchaseReturn->delete();

        return response()->json([
            'status' => 'success',
            'data' => $purchaseReturn
        ]);
    }
}
