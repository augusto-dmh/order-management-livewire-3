<?php

namespace App\Livewire;

use App\Exports\ProductsExport;
use App\Models\Country;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    public array $selectedProducts = [];

    public function export(string $format): ?BinaryFileResponse
    {
        if (empty($this->selectedProducts)) {
            $this->dispatch('swal:confirm', [
                'type'   => 'info',
                'title'  => 'No products selected.',
                'confirmButtonColor' => '#3fc3ee',
                'confirmButtonText' => 'Ok',
                'showCancelButton' => false,
            ]);
            return null;
        }
        abort_if(! in_array($format, ['csv', 'xlsx', 'pdf']), Response::HTTP_NOT_FOUND);

        return Excel::download(new ProductsExport($this->selectedProducts), 'products.' . $format);
    }

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

    public function getSelectedProductsCountProperty(): int
    {
        return count($this->selectedProducts);
    }

    public function deleteConfirm(string $method, $id = null): void
    {
        $this->dispatch('swal:confirm', [
            'type'   => 'warning',
            'title'  => 'Are you sure?',
            'text'   => $method === 'deleteSelected'
                ? 'You are about to delete ' . count($this->selectedProducts) . ' products.'
                : 'You are about to delete this product.',
            'id'     => $id,
            'method' => $method,
        ]);
    }

    #[On('delete')]
    public function delete(int $id): void
    {
        $product = Product::findOrFail($id);

        if ($product->orders()->exists()) {
            $this->addError('orderexist', 'This product cannot be deleted: it already has orders');
            return;
        }

        $product->delete();
    }

    #[On('deleteSelected')]
    public function deleteSelected()
    {
        $products = Product::with('orders')->whereIn('id', $this->selectedProducts)->get();

        foreach ($products as $product) {
            if ($product->orders->isNotEmpty()) {
                $this->addError("orderexist", "Product <span class='font-bold'>{$product->name}</span> cannot be deleted, it already has orders");
                return;
            }
        }

        $products->each->delete();

        $this->reset('selectedProducts');
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
