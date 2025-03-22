<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class CategoriesList extends Component
{
    use WithPagination;

    #[Validate]
    public $form = [
        'name' => '',
        'slug' => '',
    ];

    public function updatedFormName($value)
    {
        $this->form['slug'] = Str::slug($value);
    }

    public function updatedFormSlug($value)
    {
        $this->form['slug'] = Str::slug($value);
    }

    public function createCategory()
    {
        $this->validate();

        Category::create([
            'name' => $this->form['name'],
            'slug' => $this->form['slug'],
        ]);

        $this->form = [
            'name' => '',
            'slug' => '',
        ];
        $this->dispatch('close-modal', 'add-category');
    }

    protected function rules()
    {
        return [
            'form.name' => ['required', 'string'],
            'form.slug' => ['required', 'string', 'unique:categories,slug'],
        ];
    }

    protected function messages()
    {
        return [
            'form.name.required' => 'The name field is required.',
            'form.slug.required' => 'The slug field is required.',
            'form.name.string' => 'The name field must contain text.',
            'form.slug.string' => 'The slug field must contain text.',
            'form.slug.unique' => 'Slug field already in use. Please try another.',
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
