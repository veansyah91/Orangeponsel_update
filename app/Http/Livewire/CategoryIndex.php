<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination; 
use App\Model\Category;

class CategoryIndex extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public $paginate = 5;
    public $statusUpdate = false;
    public $search = '';

    protected $listeners = [
        'categoryStored' => 'handleStored',
        'categoryUpdated' => 'handleUpdated',
        'cancelCategoryUpdate' => 'handleCancelUpdate',
    ];

    public function render()
    {
        return view('livewire.category-index',[
            'data' => $this->search == '' ? 
                      Category::paginate($this->paginate) :
                      Category::where('nama', 'like', '%' . $this->search . '%')->paginate($this->paginate)
        ]);
    }

    public function getCategory($id){
        $this->statusUpdate = true;
        $category = Category::find($id);
        $this->emit('getCategory', $category);
    }

    public function destroy($id){
        if ($id) {
            $category = Category::find($id);
            $category->delete();
            session()->flash('success', "Kategori Berhasil Dihapus");
        }
    }

    public function handleStored(){
        session()->flash('success', "Kategori Berhasil Ditambah");
    }

    public function handleUpdated(){
        $this->statusUpdate = false;
        session()->flash('success', "Kategori Berhasil Diubah");
    }

    public function handleCancelUpdate(){
        $this->statusUpdate = false;
    }
}
