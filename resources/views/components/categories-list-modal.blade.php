<x-modal name="add-category" maxWidth="xl">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Add New Category
        </h2>

        <form wire:submit="createCategory" class="mt-4">
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" wire:model.live.debounce.500ms="form.name" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('form.name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="slug" class="block mb-2 text-sm font-medium text-gray-700">Slug</label>
                <input type="text" id="slug" wire:model.live.debounce.500ms="form.slug" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('form.slug') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-end mt-6 gap-x-3">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'add-category')">
                    Cancel
                </x-secondary-button>
                <x-primary-button type="submit">
                    Create Category
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
