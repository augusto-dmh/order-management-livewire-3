<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class CategoriesList extends Component
{
    use WithPagination;

    public ?Category $category = null;

    public string $name = '';
    public string $slug = '';

    public bool $showModal = false;

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        Category::create($this->only('name', 'slug'));

        $this->reset('name', 'slug', 'showModal');
    }

    public function updatedName(): void
    {
        $this->slug = Str::slug($this->name);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'slug' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        $categories = Category::paginate(10);

        return view('livewire.categories-list', [
            'categories' => $categories,
        ]);
    }
}
