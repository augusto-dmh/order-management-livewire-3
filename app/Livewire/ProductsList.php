<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsList extends Component
{
    use WithPagination;

    public function render()
    {
        $products = Product::with(['categories', 'country'])->paginate(10);

        return view('livewire.products-list', [
            'products' => $products,
        ]);
    }
}
