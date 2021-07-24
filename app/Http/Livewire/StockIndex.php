<?php

namespace App\Http\Livewire;

use App\Model\Stock;
use App\Model\Outlet;

use Livewire\Component;
use App\Model\OutletUser;
use App\Helpers\RoleHelper;
use Livewire\WithPagination; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $paginate = 5;
    public $search = '';

    public $showUpdate;
    public $stocks;

    public $selectOutlet;

    protected $listeners = [
        "stockStored" => "handleStore",
        "stockUpdated" => "handleUpdate",
        "cancelUpdateStock" => "handleCancelUpdate"
    ];

    public function mount()
    {
        $user = Auth::user();
        $roleUser = RoleHelper::getRole($user->id);

        if ($roleUser->name == 'SUPER ADMIN') {
            # code...
            $outlets = Outlet::all();
            $this->selectOutlet = $outlets[0]->id;
        } else {
            $outletUser = OutletUser::where('user_id', $user->id)->first();
            $this->selectOutlet = $outletUser->outlet_id;
        }
    }

    public function render()
    {
        $stocks = DB::table('stocks')->join('outlets','outlets.id','=','stocks.outlet_id')
                                                    ->join('products','products.id','=','stocks.product_id')
                                                    ->where('products.tipe','like', '%' . $this->search . '%')
                                                    ->orWhere('products.kode','like', '%' . $this->search . '%')
                                                    ->select('stocks.id','stocks.updated_at','stocks.jumlah','outlets.nama as nama_outlet','products.tipe', 'products.kode','products.category_id','stocks.outlet_id')
                                                    ->orderByDesc('stocks.jumlah','products.kode')
                                                    ->paginate($this->paginate)  ;

        // dd($this->selectOutlet);
        return view('livewire.stock-index',[
            'data' => $stocks
        ]);
    }

    public function handleStore()
    {
        session()->flash('success', "Stok Berhasil Ditambah");
    }

    public function handleUpdate()
    {
        $this->showUpdate = false;
        session()->flash('success', "Stok Berhasil Diubah");
    }

    public function handleCancelUpdate(){
        // dd();
        $this->showUpdate = false;
    }

    public function destroy($id)
    {
        if ($id) {
            $stock = Stock::find($id);
            $stock->delete();
            session()->flash('success', "Stok Berhasil Dihapus");
        }
    }

    public function getOutlet($id)
    {
        $this->showUpdate = true;
        $stock = Stock::find($id);
        $this->emit('getStock', $stock);
    }
}
