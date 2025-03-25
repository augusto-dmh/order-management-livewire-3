<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class CategoriesList extends Component
{
    use WithPagination;

    public array $addCategoryForm  = [
        'name' => '',
        'slug' => '',
    ];

    public Collection $categories;

    public bool $showModal = false;

    public array $active;

    public ?Category $categoryInEditMode;
    public array $editCategoryForm = [
        'name' => '',
        'slug' => '',
    ];

    public int $editedCategoryId = 0;

    public int $currentPage = 1;

    public int $perPage = 10;

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function toggleCategoryInEditMode(?Category $category = null): void
    {
        $this->categoryInEditMode = $category;
        $this->editCategoryForm['name'] = $category?->name;
        $this->editCategoryForm['slug'] = $category?->slug;
    }

    public function toggleIsActive(int $categoryId): void
    {
        Category::where('id', $categoryId)->update([
            'is_active' => $this->active[$categoryId],
        ]);
    }

    public function updateCategoryInEditMode(Category $category): void
    {
        $this->validate([
            'editCategoryForm.name' => ['required', 'string', 'min:3'],
            'editCategoryForm.slug' => ['nullable', 'string'],
        ]);

        $category->update([
            'name' => $this->editCategoryForm['name'],
            'slug' => $this->editCategoryForm['slug'],
        ]);

        $this->categoryInEditMode = null;
    }

    public function updatedEditCategoryFormName(): void
    {
        $this->editCategoryForm['slug'] = Str::slug($this->editCategoryForm['name']);
    }

    public function updateOrder($list)
    {
        foreach ($list as $item) {
            $cat = $this->categories->firstWhere('id', $item['value']);
            $order = $item['order'] + (($this->currentPage - 1) * $this->perPage);

            if ($cat['position'] != $order) {
                Category::where('id', $item['value'])->update(['position' => $order]);
            }
        }
    }

    public function save()
    {
        $this->validate([
            'addCategoryForm.name' => ['required', 'string', 'min:3'],
            'addCategoryForm.slug' => ['nullable', 'string'],
        ]);

        $position = Category::max('position') + 1;
        Category::create([
            'name' => $this->addCategoryForm['name'],
            'slug' => $this->addCategoryForm['slug'],
            'position' => $position,
        ]);

        $this->reset('addCategoryForm', 'showModal');
    }

    public function updatedAddCategoryFormName(): void
    {
        $this->addCategoryForm['slug'] = Str::slug($this->addCategoryForm['name']);
    }

    public function render()
    {
        $cats = Category::orderBy('position', 'desc')->paginate($this->perPage);
        $links = $cats->links();
        $this->currentPage = $cats->currentPage();
        $this->categories = collect($cats->items())->keyBy('id');

        $this->active = $this->categories->mapWithKeys(
            fn(Category $item) => [$item['id'] => (bool) $item['is_active']],
        )->toArray();

        return view('livewire.categories-list', [
            'links' => $links,
        ]);
    }
}
