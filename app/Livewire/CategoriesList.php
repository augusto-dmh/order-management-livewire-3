<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class CategoriesList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.categories-list', [
            'categories' => Category::query()->paginate(10),
        ]);
    }
}
