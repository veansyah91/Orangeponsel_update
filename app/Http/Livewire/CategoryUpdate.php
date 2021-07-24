<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Category;

class CategoryUpdate extends Component
{
    public $nama;
    public $categoryId;

    protected $listeners = [
        'getCategory' => "showCategory" 
    ];


    public function render()
    {
        return view('livewire.category-update');
    }

    public function update(){
        $this->validate([
            'nama' => 'required'
        ]);

        $category = Category::find($this->categoryId)->update([
            'nama' => strtoupper($this->nama)
        ]);

        // auto reload
        $this->emit('categoryUpdated');
    }

    public function showCategory($category){
        $this->nama = $category['nama'];
        $this->categoryId = $category['id'];
    }

    public function cancelUpdate(){
        $this->emit('cancelCategoryUpdate');
    }
}
