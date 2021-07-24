<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Category;


class CategoryCreate extends Component
{
    public $nama;

    public function render()
    {
        return view('livewire.category-create');
    }

    public function store(){
        $this->validate([
            'nama' => 'required'
        ]);

        $category = Category::create([
            'nama' => strtoupper($this->nama)
        ]);

        // mengosongkan form input
        $this->resetInput();

        // auto reload
        $this->emit('categoryStored', $category);
    }

    private function resetInput(){
        $this->nama = null;
    }
}
