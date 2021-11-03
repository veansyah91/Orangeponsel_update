<?php

namespace App\Http\Livewire;

use App\Model\Stock;
use App\Model\Outlet;

use App\Model\Category;
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
        $this->selectOutlet = '';
    }

    public function render()
    {
        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();

        $stocks = DB::table('stocks')->join('outlets','outlets.id','=','stocks.outlet_id')
                                    ->join('products','products.id','=','stocks.product_id')
                                    ->join('categories','categories.id','=','products.category_id')
                                    ->where('stocks.outlet_id','like', '%' . $this->selectOutlet . '%')
                                    ->where(function($query) {
                                        $query->where('products.tipe','like', '%' . $this->search . '%')
                                            ->orWhere('products.kode','like', '%' . $this->search . '%');
                                    })
                                    
                                    ->select('stocks.id','stocks.updated_at','stocks.jumlah','outlets.nama as nama_outlet','products.tipe', 'products.kode','products.category_id','stocks.outlet_id','stocks.item_entry_id', 'categories.nama as category_name')
                                    ->orderByDesc('stocks.jumlah','products.kode')
                                    ->paginate($this->paginate)  ;

        $categories = Category::all();
        $outlets = Outlet::all();

        return view('livewire.stock-index',[
            'data' => $stocks,
            'categories' => $categories,
            'outlets' => $outlets,
            'outletUser' => $outletUser
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
