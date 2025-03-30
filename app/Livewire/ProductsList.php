<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class ProductsList extends Component
{
    use WithPagination;

    #[Url(except: null)]
    public $name = null;
    #[Url(except: null)]
    public $category = null;
    #[Url(except: null)]
    public $country = null;
    #[Url(except: null)]
    public $price_from = null;
    #[Url(except: null)]
    public $price_to = null;

    public $categoriesPerProductToShow = 2;

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['name', 'category', 'country', 'price_from', 'price_to']);
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::with(['categories', 'country'])
            ->when($this->name, function (Builder $q) {
                $q->where('name', 'like', '%' . $this->name . '%');
            })
            ->when($this->category, function (Builder $q) {
                $q->whereHas('categories', function (Builder $q) {
                    $q->where('categories.id', $this->category);
                });
            })
            ->when($this->country, function (Builder $q) {
                $q->where('country_id', $this->country);
            })
            ->when($this->price_from, function (Builder $q) {
                $q->where('price', '>=', $this->price_from * 100);
            })
            ->when($this->price_to, function (Builder $q) {
                $q->where('price', '<=', $this->price_to * 100);
            })
            ->paginate(10);
        $countries = Country::all();
        $categories = Category::all();

        return view('livewire.products-list', [
            'products' => $products,
            'countries' => $countries,
            'categories' => $categories,
        ]);
    }
}
