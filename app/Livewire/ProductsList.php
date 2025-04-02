<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductsList extends Component
{
    use WithPagination;

    public array $searchColumns = [
        'name' => '',
        'category' => '',
        'country' => '',
        'price' => [
            'from' => '',
            'to' => '',
        ],
    ];

    public $columnSorting = ['columnName' => 'name', 'sortingOrder' => 'asc'];

    public $categoriesPerProductToShow = 2;

    public ?Collection $categories = null;
    public ?Collection $countries = null;

    public function toggleColumnSorting(string $columnName): void
    {
        if ($columnName == $this->columnSorting['columnName']) {
            if ($this->columnSorting['sortingOrder'] == 'asc') {
                $this->columnSorting['sortingOrder'] = 'desc';
            } else {
                $this->reset('columnSorting');
            }
        } else {
            $this->columnSorting['sortingOrder'] = 'asc';
            $this->columnSorting['columnName'] = $columnName;
        }
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('searchColumns');
        $this->resetPage();
    }

    public function mount()
    {
        $this->categories = Category::select('id', 'name')->get();
        $this->countries = Country::select('id', 'name')->get();
    }

    public function render()
    {
        $products = Product::query()
            ->select(['products.*', 'countries.name as countryName'])
            ->join('countries', 'products.country_id', 'countries.id')
            ->with(['categories'])
            ->when($this->searchColumns['name'], function (Builder $q) {
                $q->where('products.name', 'like', '%' . $this->searchColumns['name'] . '%');
            })
            ->when($this->searchColumns['category'], function (Builder $q) {
                $q->whereRelation('categories', 'categories.id', $this->searchColumns['category']);
            })
            ->when($this->searchColumns['country'], function (Builder $q) {
                $q->where('country_id', $this->searchColumns['country']);
            })
            ->when($this->searchColumns['price']['from'], function (Builder $q) {
                $q->where('price', '>=', $this->searchColumns['price']['from'] * 100);
            })
            ->when($this->searchColumns['price']['to'], function (Builder $q) {
                $q->where('price', '<=', $this->searchColumns['price']['to'] * 100);
            })
            ->orderBy($this->columnSorting['columnName'], $this->columnSorting['sortingOrder'])
            ->paginate(10);

        return view('livewire.products-list', [
            'products' => $products,
        ]);
    }
}
