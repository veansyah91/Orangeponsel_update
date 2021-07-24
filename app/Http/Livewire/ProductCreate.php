<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Supplier;
use App\Model\Category;
use App\Model\Brand;
use App\Model\Product;

class ProductCreate extends Component
{
    public $brandId;
    public $categoryId;
    public $supplierId;

    public $supplier;
    public $category;
    public $brand;
    public $kode;
    public $tipe;
    public $modal = 1000;
    public $jual = 1000;

    public $supplierLists;
    public $categoryLists;
    public $brandLists;

    public $supplierInputList;
    public $categoryInputList;
    public $brandInputList;

    public function mount()
    {
        $this->resetListInput();
    }

    public function render()
    {
        $this->supplierInputLists = Supplier::where('nama', 'like', '%' . $this->supplier . '%')->skip(0)->take(5)->get();

        $this->categoryInputLists = Category::where('nama', 'like', '%' . $this->category . '%')->skip(0)->take(5)->get();

        $this->brandInputLists = Brand::where('nama', 'like', '%' . $this->brand . '%')->skip(0)->take(5)->get();
        return view('livewire.product-create',[
            'supplierInputLists' => $this->supplierInputLists,
            'categoryInputLists' => $this->categoryInputLists,
            'brandInputLists' => $this->brandInputLists
        ]);
    }

    public function resetListInput(){
        $this->supplierInputList = false;
        $this->categoryInputList = false;
        $this->brandInputList = false;
    }

    public function resetAllInput()
    {
        $this->supplier = null;
        $this->category = null;
        $this->brand = null;
        $this->kode = null;
        $this->tipe = null;
        $this->modal = 1000;
        $this->jual = 1000;
    }

    public function showSearchSupplier()
    {
        $this->supplierInputList ? $this->supplierInputList = false : $this->supplierInputList = true;
    }

    public function inputSearchSupplier()
    {
        $this->supplierInputList = true;
    }

    public function selectSupplier($supplierId, $nama)
    {
        $this->supplier = $nama;
        $this->supplierId = $supplierId;
        $this->resetListInput();
    }

    public function showSearchCategory()
    {
        $this->categoryInputList ? $this->categoryInputList = false : $this->categoryInputList = true;
        
    }

    public function inputSearchCategory()
    {
        $this->categoryInputList = true;
    }

    public function selectCategory($categoryId, $nama){
        $this->category = $nama;
        $this->categoryId = $categoryId;
        $this->resetListInput();
    }

    public function showSearchBrand()
    {
        $this->brandInputList ? $this->brandInputList = false : $this->brandInputList = true;
    }

    public function inputSearchBrand()
    {
        $this->brandInputList = true;
    }

    public function selectBrand($brandId, $nama)
    {
        $this->brand = $nama;
        $this->brandId = $brandId;
        $this->tipe = $nama . ' ';
        $this->resetListInput();
    }

    public function storeProduct()
    {
        // dd($this->supplier);
        $this->validate([
            'kode' => 'required|unique:products',
            'brandId' => 'required',
            'categoryId' => 'required',
            'supplierId' => 'required',
            'tipe' => 'required',
        ]);

        $product = Product::create([
            'brand_id' => $this->brandId,
            'category_id' => $this->categoryId,
            'supplier_id' => $this->supplierId,
            'kode' => $this->kode,
            'tipe' => $this->tipe,
            'modal' => $this->modal,
            'jual' => $this->jual,
        ]);


        $this->kode = null;
        session()->flash('success', "Produk Berhasil Ditambah");
    }  

    public function showProduct()
    {
        $this->resetListInput();
        $this->emit('showProduct');
    }
}
