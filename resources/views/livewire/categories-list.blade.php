<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <x-primary-button wire:click="openModal" type="button" class="mb-4">
                        Add Category
                    </x-primary-button>

                    <div class="min-w-full mb-4 overflow-hidden overflow-x-auto align-middle sm:rounded-md">
                        <table class="min-w-full border divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="w-10 px-6 py-3 text-left bg-gray-50">
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Name</span>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Slug</span>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Active</span>
                                    </th>
                                    <th class="w-56 px-6 py-3 text-left bg-gray-50">
                                    </th>
                                </tr>
                            </thead>

                            <tbody wire:sortable="updateOrder" class="bg-white divide-y divide-gray-200 divide-solid">
                                @foreach($categories as $category)
                                    <tr class="bg-white" wire:sortable.item="{{ $category->id }}" wire:key="{{ $loop->index }}">
                                        <td class="px-6">
                                            <button wire:sortable.handle class="cursor_move">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                                                    <path fill="none" d="M0 0h256v256H0z" />
                                                    <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" d="M156.3 203.7 128 232l-28.3-28.3M128 160v72M99.7 52.3 128 24l28.3 28.3M128 96V24M52.3 156.3 24 128l28.3-28.3M96 128H24M203.7 99.7 232 128l-28.3 28.3M160 128h72" />
                                                </svg>
                                            </button>
                                        </td>

                                        {{-- Inline Edit Start --}}
                                        <td class="@if($editedCategoryId !== $category->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <x-text-input wire:model.live.debounce="name" id="name" class="w-full py-2 pl-2 pr-4 text-sm border border-gray-400 rounded-lg sm:text-base focus:outline-none focus:border-blue-400" />
                                            @error('name')
                                                <span class="text-sm text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="@if($editedCategoryId !== $category->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <x-text-input wire:model="slug" id="slug" class="w-full py-2 pl-2 pr-4 text-sm border border-gray-400 rounded-lg sm:text-base focus:outline-none focus:border-blue-400" />
                                            @error('slug')
                                                <span class="text-sm text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        {{-- Inline Edit End --}}

                                        {{-- Show Category Name/Slug Start --}}
                                        <td class="@if($editedCategoryId === $category->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $category->name }}
                                        </td>
                                        <td class="@if($editedCategoryId === $category->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $category->slug }}
                                        </td>
                                        {{-- Show Category Name/Slug End --}}

                                        <td class="px-6">
                                            <div class="relative inline-block w-10 mr-2 align-middle transition duration-200 ease-in select-none">
                                                <input wire:model="active.{{ $category->id }}" wire:click="toggleIsActive({{ $category->id }})" type="checkbox" name="toggle" id="{{ $loop->index.$category->id }}" class="absolute block w-6 h-6 bg-white border-4 rounded-full appearance-none cursor-pointer focus:outline-none toggle-checkbox" />
                                                <label for="{{ $loop->index.$category->id }}" class="block h-6 overflow-hidden bg-gray-300 rounded-full cursor-pointer toggle-label"></label>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            @if($editedCategoryId === $category->id)
                                                <x-primary-button wire:click="save">
                                                    Save
                                                </x-primary-button>
                                                <x-primary-button wire:click.prevent="cancelCategoryEdit">
                                                    Cancel
                                                </x-primary-button>
                                            @else
                                                <x-primary-button wire:click="editCategory({{ $category->id }})">
                                                    Edit
                                                </x-primary-button>
                                                <button wire:click="$set('categoryToBeDeletedId', {{ $category->id }})" class="px-4 py-2 text-xs text-red-500 uppercase bg-red-200 border border-transparent rounded-md hover:text-red-700 hover:bg-red-300">
                                                    Delete
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $links !!}
                </div>
            </div>
        </div>
    </div>

    <div class="@if (!$showModal) hidden @endif flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-90">
        <div class="w-1/2 bg-white rounded-lg">
            <form wire:submit.prevent="save" class="w-full">
                <div class="flex flex-col items-start p-4">
                    <div class="flex items-center w-full pb-4 mb-4 border-b">
                        <div class="text-lg font-medium text-gray-900">Create Category</div>
                        <svg wire:click.prevent="$set('showModal', false)"
                             class="w-6 h-6 ml-auto text-gray-700 cursor-pointer fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z" />
                        </svg>
                    </div>
                    <div class="w-full mb-2">
                        <label class="block text-sm font-medium text-gray-700" for="name">
                            Name
                        </label>
                        <input wire:model.live.debounce="name" id="name"
                               class="w-full py-2 pl-2 pr-4 mt-2 text-sm border border-gray-400 rounded-lg sm:text-base focus:outline-none focus:border-blue-400" />
                        @error('name')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-2">
                        <label class="block text-sm font-medium text-gray-700" for="slug">
                            Slug
                        </label>
                        <input wire:model="slug" id="slug"
                               class="w-full py-2 pl-2 pr-4 mt-2 text-sm border border-gray-400 rounded-lg sm:text-base focus:outline-none focus:border-blue-400" />
                        @error('slug')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4 ml-auto">
                        <button class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700" type="submit">
                            Create
                        </button>
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 font-bold text-white bg-gray-500 rounded" type="button" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
        class="@if (!$categoryToBeDeletedId) hidden @endif flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-90"
        wire:click.self="cancelCategoryDeletion"
    >
        <div class="p-5 text-center bg-white rounded-lg w-96">
            <div class="flex flex-col items-center justify-center">
                <!-- Exclamation Symbol Circle -->
                <div class="flex items-center justify-center w-24 h-24 mb-5 bg-red-100 rounded-full">
                    <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="mb-3 text-2xl font-bold text-gray-900">Are you sure?</h3>

                <!-- Description -->
                <p class="mb-6 text-gray-700">
                    You won't be able to revert this category deletion!
                </p>

                <!-- Buttons -->
                <div class="flex flex-col w-full space-y-3">
                    <button wire:click="deleteCategory({{ $categoryToBeDeletedId }})" class="w-full py-3 font-medium text-white bg-red-500 rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300" type="button">
                        Yes, delete it!
                    </button>
                    <button wire:click="cancelCategoryDeletion" class="w-full py-2 font-medium text-gray-700 bg-gray-200 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300" type="button">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
