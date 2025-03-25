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

    public ?Category $category = null;

    public string $name = '';
    public string $slug = '';

    public Collection $categories;

    public bool $showModal = false;

    public array $active;

    public int $editedCategoryId = 0;

    public int $currentPage = 1;

    public int $perPage = 10;

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function toggleIsActive(int $categoryId): void
    {
        Category::where('id', $categoryId)->update([
            'is_active' => $this->active[$categoryId],
        ]);
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
        $this->validate();

        $position = Category::max('position') + 1;
        Category::create(array_merge($this->only('name', 'slug'), ['position' => $position]));

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
        $cats = Category::orderBy('position')->paginate($this->perPage);
        $links = $cats->links();
        $this->currentPage = $cats->currentPage();
        $this->categories = collect($cats->items());

        $this->active = $this->categories->mapWithKeys(
            fn(Category $item) => [$item['id'] => (bool) $item['is_active']],
        )->toArray();

        return view('livewire.categories-list', [
            'links' => $links,
        ]);
    }
}
