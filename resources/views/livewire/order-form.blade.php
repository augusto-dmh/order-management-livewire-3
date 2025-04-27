<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $order && $order->exists ? 'Edit Order #' . $order->id : 'Create Order' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session()->has('message'))
                        <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="save">
                        @csrf

                        <div>
                            <x-input-label class="mb-1" for="user_id" :value="__('Customer')" />

                            <x-select2
                                class="mt-1"
                                id="user_id"
                                name="user_id"
                                :options="$this->listsForFields['users']"
                                wire:model="user_id"
                                :selectedOptions="$user_id"
                            />
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label class="mb-1" for="order_date" :value="__('Order date')" />

                            <input x-data
                                     x-init="new Pikaday({ field: $el, format: 'MM/DD/YYYY' })"
                                     type="text"
                                     id="order_date"
                                     wire:model.blur="order_date"
                                     autocomplete="off"
                                     class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            <x-input-error :messages="$errors->get('order_date')" class="mt-2" />
                        </div>

                        {{-- Order Products --}}
                        <div class="mt-6">
                            <h3 class="text-lg font-medium">Order Products</h3>

                            @if(count($orderProducts) === 0)
                                <div class="p-4 mt-2 text-gray-500 bg-gray-100 rounded">
                                    No products added to this order yet.
                                </div>
                            @else
                                <table class="min-w-full mt-4 border divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left bg-gray-50">
                                                <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Product</span>
                                            </th>
                                            <th class="px-6 py-3 text-left bg-gray-50">
                                                <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Price</span>
                                            </th>
                                            <th class="px-6 py-3 text-left bg-gray-50">
                                                <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Quantity</span>
                                            </th>
                                            <th class="px-6 py-3 text-left bg-gray-50">
                                                <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Subtotal</span>
                                            </th>
                                            <th class="w-56 px-6 py-3 text-left bg-gray-50"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                        @foreach($orderProducts as $index => $product)
                                            <tr>
                                                <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                                    <select
                                                        wire:model.live="orderProducts.{{ $index }}.product_id"
                                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    >
                                                        <option value="">-- Select Product --</option>
                                                        @foreach($allProducts as $listProduct)
                                                            <option value="{{ $listProduct->id }}">{{ $listProduct->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('orderProducts.' . $index . '.product_id')" class="mt-2" />
                                                </td>
                                                <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                                    ${{ number_format($product['price'] / 100, 2) }}
                                                </td>
                                                <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                                    <input
                                                        type="number"
                                                        wire:model.live="orderProducts.{{ $index }}.quantity"
                                                        class="w-24 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                        min="1"
                                                    >
                                                    <x-input-error :messages="$errors->get('orderProducts.' . $index . '.quantity')" class="mt-2" />
                                                </td>
                                                <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                                    ${{ number_format(($product['subtotal'] ?? 0) / 100, 2) }}
                                                </td>
                                                <td class="px-6 py-4 text-sm leading-5 text-right whitespace-no-wrap">
                                                    <button
                                                        type="button"
                                                        wire:click="removeProduct({{ $index }})"
                                                        class="px-4 py-2 ml-1 text-xs text-red-500 uppercase bg-red-200 border border-transparent rounded-md hover:text-red-700 hover:bg-red-300"
                                                    >
                                                        Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            <div class="mt-3">
                                <x-primary-button type="button" wire:click="addProduct">+ Add Product</x-primary-button>
                            </div>
                        </div>
                        {{-- End Order Products --}}

                        <div class="flex justify-end mt-6">
                            <table>
                                <tr>
                                    <th class="p-2 text-left">Subtotal</th>
                                    <td class="p-2">${{ number_format($subtotal / 100, 2) }}</td>
                                </tr>
                                <tr class="text-left border-t border-gray-300">
                                    <th class="p-2">Taxes ({{ $taxesPercent }}%)</th>
                                    <td class="p-2">
                                        ${{ number_format($taxes / 100, 2) }}
                                    </td>
                                </tr>
                                <tr class="text-left border-t border-gray-300">
                                    <th class="p-2">Total</th>
                                    <td class="p-2">${{ number_format($total / 100, 2) }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="flex gap-2 mt-6">
                            <x-primary-button type="submit">
                                {{ $order && $order->exists ? 'Update Order' : 'Create Order' }}
                            </x-primary-button>
                            <x-secondary-button type="button" onclick="window.history.back();">
                                Cancel
                            </x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush
