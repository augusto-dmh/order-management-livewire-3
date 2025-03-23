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

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @foreach($categories as $category)
                                    <tr class="bg-white">
                                        <td class="px-6">
                                            <button>
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                                                    <path fill="none" d="M0 0h256v256H0z" />
                                                    <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" d="M156.3 203.7 128 232l-28.3-28.3M128 160v72M99.7 52.3 128 24l28.3 28.3M128 96V24M52.3 156.3 24 128l28.3-28.3M96 128H24M203.7 99.7 232 128l-28.3 28.3M160 128h72" />
                                                </svg>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $category->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $category->slug }}
                                        </td>
                                        <td class="px-6">
                                            <div class="relative inline-block w-12 mr-2 align-middle transition duration-200 ease-in select-none">
                                                <input
                                                    type="checkbox"
                                                    id="is-active-{{ $category->id }}"
                                                    wire:key="is-active-{{ $category->id }}"
                                                    wire:click="activeToggle({{ $category }})"
                                                    wire:loading.attr="disabled"
                                                    {{ $category->is_active ? 'checked' : '' }}
                                                    class="sr-only peer"
                                                />
                                                <label
                                                    for="is-active-{{ $category->id }}"
                                                    class="block h-6 overflow-hidden rounded-full cursor-pointer
                                                           peer-checked:bg-green-400 bg-gray-300
                                                           after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                                           after:bg-white after:rounded-full after:h-5 after:w-5
                                                           after:transition-all peer-checked:after:translate-x-6 peer-checked:after:bg-green-700"
                                                ></label>

                                                @if($category->is_active)
                                                    <svg class="absolute w-3 h-3 text-white transform -translate-y-1/2 pointer-events-none top-1/2 right-1.5 z-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <x-primary-button>
                                                Edit
                                            </x-primary-button>
                                            <button class="px-4 py-2 text-xs text-red-500 uppercase bg-red-200 border border-transparent rounded-md hover:text-red-700 hover:bg-red-300">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $categories->links() !!}
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
</div>
