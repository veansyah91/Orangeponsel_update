<?php

namespace App\Http\Controllers\Admin;

use App\Model\Stock;
use App\Model\Outlet;
use App\Model\Invoice;
use App\Model\OutletUser;
use App\Model\InvoiceDetail;
use App\Model\OutletInvoice;
use Illuminate\Http\Request;
use App\Model\AccountReceivable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $outletUser = OutletUser::where('user_id', $user->id)
                                ->first();

        return view('admin.daily.invoice.index', [
            'outlets' => Outlet::all(),
            'outlet' => Outlet::find($outletUser->outlet_id),
            'outlet_id' => $outletUser->outlet_id,
        ]);
    }

    public function getInvoiceNumber(Request $request){
        $user = Auth::user();

        $invoice = Invoice::where('outlet_id', $request->outlet_id)->get()->last();
        $outletId = $request->outlet_id;
        // return json
        return response()->json([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    public function create(Request $request)
    {
        // cek apakah invoice sudah ada atau belum
        $invoice = Invoice::where('outlet_id', $request->outletId)->get()->last();

        if($request->nomor_nota == $invoice->nomor_nota){
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor Nota sudah ada'
            ]);

        }

        // create new invoice
        $invoice = Invoice::create([
            'outlet_id' => $request->outletId,
            'customer_id' => $request->pelangganId,
            'no_nota' => $request->nomor_nota,
        ]);

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
        }

        if ($request->totalBayar < $total) {
            AccountReceivable::create([
                'invoice_id' => $invoice->id,
                'remaining' => $total - $request->totalBayar,
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
        return view('admin.daily.invoice.balance');
    }
}
