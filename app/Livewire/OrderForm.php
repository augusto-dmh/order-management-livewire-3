<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class OrderForm extends Component
{
    public ?Order $order = null;

    public ?int $user_id = null;
    public string $order_date = '';
    public int $subtotal = 0;
    public int $taxes = 0;
    public int $total = 0;

    public array $orderProducts = [];

    public Collection $allProducts;

    public array $listsForFields = [];

    public int $taxesPercent = 0;

    public function mount(Order $order): void
    {
        $this->initListsForFields();
        $this->taxesPercent = config('app.orders.taxes');

        // On accessing Edit or Create route
        if ($order->exists) {
            $this->order = $order;
            $this->user_id = $this->order->user_id;
            $this->order_date = Carbon::parse($this->order->order_date)->format('Y-m-d');
            $this->subtotal = $this->order->subtotal;
            $this->taxes = $this->order->taxes;
            $this->total = $this->order->total;
            $this->loadOrderProducts();
        } else {
            $this->order_date = now()->format('Y-m-d');
        }
    }

    public function render(): View
    {
        return view('livewire.order-form');
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'order_date' => ['required', 'date'],
            'subtotal' => ['required', 'numeric'],
            'taxes' => ['required', 'numeric'],
            'total' => ['required', 'numeric'],
            'orderProducts' => ['array'],
            'orderProducts.*.product_id' => ['required', 'exists:products,id'], // Ensure product exists
            'orderProducts.*.quantity' => ['required', 'integer', 'min:1'], // Ensure quantity > 0
        ];
    }

    public function messages(): array
    {
        return [
            'orderProducts.*.product_id.required' => 'Each product must have a valid product selected.',
            'orderProducts.*.product_id.exists' => 'The selected product does not exist.',
            'orderProducts.*.quantity.required' => 'Each product must have a quantity specified.',
            'orderProducts.*.quantity.min' => 'The quantity for each product must be greater than 0.',
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['users'] = User::pluck('name', 'id')->toArray();
        $this->allProducts = Product::all();
    }

    public function addProduct(): void
    {
        $this->orderProducts[] = [
            'product_id' => null,
            'quantity' => 1,
            'price' => 0,
            'subtotal' => 0,
        ];
    }

    public function removeProduct(int $index): void
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
        $this->calculateTotals();
    }

    public function updatedOrderProducts(): void
    {
        $this->calculateTotals();
    }

    public function loadOrderProducts(): void
    {
        foreach ($this->order->products as $orderProduct) {
            $this->orderProducts[] = [
                'product_id' => $orderProduct->id,
                'quantity' => $orderProduct->pivot->quantity,
                'price' => $orderProduct->price,
                'subtotal' => $orderProduct->price * $orderProduct->pivot->quantity,
            ];
        }
    }

    public function calculateTotals(): void
    {
        $this->subtotal = 0;

        foreach ($this->orderProducts as $index => $item) {
            if (empty($item['product_id'])) {
                continue;
            }

            $product = $this->allProducts->firstWhere('id', $item['product_id']);
            if ($product) {
                $price = $product->price;
                $quantity = $item['quantity'];
                $this->orderProducts[$index]['price'] = $price;
                $this->orderProducts[$index]['subtotal'] = $price * $quantity;
                $this->subtotal += $price * $quantity;
            }
        }

        $this->taxes = round($this->subtotal * $this->taxesPercent / 100);
        $this->total = $this->subtotal + $this->taxes;
    }

    public function getProductNameById($productId)
    {
        if (!$productId) return '';
        $product = $this->allProducts->firstWhere('id', $productId);
        return $product ? $product->name : '';
    }

    public function save()
    {
        $validated = $this->validate();

        if (!$this->order) {
            $this->order = new Order();
        }

        $this->order->user_id = $this->user_id;
        $this->order->order_date = $this->order_date;
        $this->order->subtotal = $this->subtotal;
        $this->order->taxes = $this->taxes;
        $this->order->total = $this->total;
        $this->order->save();

        // Sync order products
        $syncData = [];
        foreach ($this->orderProducts as $product) {
            if (!empty($product['product_id'])) {
                $syncData[$product['product_id']] = [
                    'quantity' => $product['quantity'],
                    'price' => $product['price']
                ];
            }
        }

        $this->order->products()->sync($syncData);

        session()->flash('message', 'Order saved successfully.');

        return redirect()->route('orders.index');
    }
}
