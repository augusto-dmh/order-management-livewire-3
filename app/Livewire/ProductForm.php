<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

class ProductForm extends Component
{
    public ?Product $product = null;

    public string $name = '';
    public string $description = '';
    public ?float $price = null;
    public ?int $country_id;

    public bool $editing  = false;

    public array $categories = [];

    public array $listsForFields = [];

    protected function initListsForFields(): void
    {
        $this->listsForFields['countries'] = Country::pluck('name', 'id')->toArray();

        $this->listsForFields['categories'] = Category::active()->pluck('name', 'id')->toArray();
    }

    public function save(): void
    {
        $this->validate();

        if (is_null($this->product)) {
            $this->product = Product::create(
                array_merge(
                    $this->only('name', 'description', 'country_id'),
                    ['price' => $this->price * 100]
                )
            );
        } else {
            $this->product->update(
                array_merge(
                    $this->only('name', 'description', 'country_id'),
                    ['price' => $this->price * 100]
                )
            );
        }

        $this->product->categories()->sync($this->categories);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'price' => ['required'],
            'categories' => ['required', 'array']
        ];
    }

    public function mount(Product $product): void
    {
        $this->initListsForFields();

        if (!is_null($this->product)) {
            $this->product = $product;
            $this->editing = true;

            $this->name = $this->product->name;
            $this->description = $this->product->description;
            $this->price = number_format($this->product->price / 100, 2);
            $this->country_id = $this->product->country_id;

            $this->categories = $this->product->categories()->pluck('id')->toArray();
        }
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}
