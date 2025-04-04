<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="mb-4">
                        <div class="flex justify-between mb-4">
                            <div>
                                <x-primary-button wire:click="" type="button">
                                    Create Product
                                </x-primary-button>

                                <x-primary-button
                                    wire:click="deleteConfirm('deleteSelected')"
                                    wire:loading.attr="disabled"
                                    type="button"
                                    class="bg-red-500 hover:bg-red-700 disabled:opacity-50"
                                    :disabled="!$this->selectedProductsCount"
                                >
                                    Delete Selected
                                </x-primary-button>
                            </div>

                            <div>
                                <x-primary-button wire:click="export('csv')">CSV</x-primary-button>
                                <x-primary-button wire:click="export('xlsx')">XLSX</x-primary-button>
                                <x-primary-button wire:click="export('pdf')">PDF</x-primary-button>
                            </div>
                        </div>
                    </div>

                    <div class="min-w-full mb-4 overflow-hidden overflow-x-auto align-middle sm:rounded-md">
                        <table class="min-w-full border divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <button wire:click="toggleColumnSorting('name')" class="flex items-center text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">
                                            <span>Name</span>
                                            @if($columnSorting['columnName'] === 'name')
                                               <x-dynamic-component :component="'sort-' . $columnSorting['sortingOrder']" />
                                            @else
                                                <x-sort />
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Categories</span>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <button wire:click="toggleColumnSorting('country_id')" class="flex items-center text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">
                                            <span>Country</span>
                                            @if($columnSorting['columnName'] === 'country_id')
                                               <x-dynamic-component :component="'sort-' . $columnSorting['sortingOrder']" />
                                            @else
                                                <x-sort />
                                            @endif
                                        </button>
                                    </th>
                                    <th class="w-32 px-6 py-3 text-left bg-gray-50">
                                        <button wire:click="toggleColumnSorting('price')" class="flex items-center text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">
                                            <span>Price</span>
                                            @if($columnSorting['columnName'] === 'price')
                                               <x-dynamic-component :component="'sort-' . $columnSorting['sortingOrder']" />
                                            @else
                                                <x-sort />
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                    </th>
                                </tr>
                                <!-- Filter row -->
                                <tr class="bg-gray-100">
                                    <td class="px-4 py-2"></td>
                                    <td class="px-6 py-2">
                                        <input type="text" wire:model.live.debounce="searchColumns.name" placeholder="Search..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-6 py-2">
                                        <select wire:model.live.debounce="searchColumns.category" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-2">
                                        <select wire:model.live.debounce="searchColumns.country" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            <option value="">All Countries</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-2">
                                        <div class="flex flex-col gap-2">
                                            <input type="number" wire:model.live.debounce="searchColumns.price.from" placeholder="Min" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            <input type="number" wire:model.live.debounce="searchColumns.price.to" placeholder="Max" class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <button wire:click="resetFilters" class="px-3 py-2 text-xs text-gray-600 uppercase bg-gray-200 border border-transparent rounded-md hover:bg-gray-300">
                                            Reset
                                        </button>
                                    </td>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @foreach($products as $product)
                                    <tr class="bg-white" wire:key="product-{{ $product->id }}">
                                        <td class="px-4 py-2 text-sm leading-5 text-gray-900 whitespace-nowrap">
                                            <input type="checkbox" value="{{ $product->id }}" wire:model.live="selectedProducts">
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-nowrap">
                                            {{ $product->name }}
                                        </td>
                                        <td class="flex gap-1 px-6 py-4 text-sm leading-5 text-gray-900 whitespace-nowrap">
                                            @foreach($product->categories()->take($categoriesPerProductToShow)->get() as $category)
                                                <span class="px-2 py-1 text-xs text-indigo-700 bg-indigo-200 rounded-md">
                                                    {{ Str::limit($category->name, 20) }}
                                                </span>
                                            @endforeach
                                            @if($product->categories()->count() - $categoriesPerProductToShow > 0)
                                                <span class="px-2 py-1 text-xs text-indigo-700 bg-indigo-200 rounded-md">
                                                    +{{ $product->categories()->count() - $categoriesPerProductToShow }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-nowrap">
                                            {{ $product->country->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-nowrap">
                                            ${{ number_format($product->price / 100, 2) }}
                                        </td>
                                        <td class="flex items-center justify-center gap-1 px-4 py-4 text-sm leading-5 text-gray-900 whitespace-nowrap">
                                            <x-primary-button wire:click="">
                                                Edit
                                            </x-primary-button>
                                            <button wire:click="deleteConfirm('delete', {{ $product->id }})" class="px-4 py-2 text-xs text-red-500 uppercase bg-red-200 border border-transparent rounded-md hover:text-red-700 hover:bg-red-300">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $products->links() }}

                </div>
            </div>
        </div>
    </div>
</div>
