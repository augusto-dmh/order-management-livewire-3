<!-- filepath: d:\GithubRepositorios\order-management-livewire-3\resources\views\livewire\categories-list.blade.php -->
<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Categories') }}
        </h2>
        <x-primary-button wire:click="" type="button">
            Add Category
        </x-primary-button>
    </div>
</x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl rounded-xl">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="w-10 px-6 py-4 text-left bg-gray-50" style="text-align: left;">
                                    Name
                                </th>
                                <th class="w-10 px-6 py-4 text-left bg-gray-50" style="text-align: left;">
                                    Slug
                                </th>
                                <th class="w-10 px-6 py-4 text-center bg-gray-50">
                                    Status
                                </th>
                                <th class="w-10 px-6 py-4 text-center bg-gray-50">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="w-10 px-6 py-4 text-left bg-gray-50">
                                        <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                    </td>
                                    <td class="w-10 px-6 py-4 text-left bg-gray-50">
                                        <div class="text-sm text-gray-600">{{ $category->slug }}</div>
                                    </td>
                                    <td class="w-10 px-6 py-4 text-center bg-gray-50">
                                        <div class="flex items-center justify-center">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer" {{ $category->is_active ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300/20 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="w-10 px-6 py-4 text-center bg-gray-50">
                                        <x-secondary-button>
                                            Edit
                                        </x-secondary-button>
                                        <x-danger-button>
                                            Delete
                                        </x-danger-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-6 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <span class="mt-2 text-sm font-medium text-gray-500">No categories found</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
