<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">


                @if(empty($cart))
                    <p class="text-gray-400 text-center py-12">Your cart is empty. <a href="{{ route('dashboard') }}" class="text-blue-400 underline">Browse items</a></p>
                @else
                    <table class="w-full text-sm text-gray-200">
                        <thead>
                            <tr class="border-b border-gray-600 text-left text-gray-400">
                                <th class="pb-3">Item</th>
                                <th class="pb-3">Price</th>
                                <th class="pb-3">Qty</th>
                                <th class="pb-3">Subtotal</th>
                                <th class="pb-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $item)
                                <tr class="border-b border-gray-700">
                                    <!-- Image + Name -->
                                    <td class="py-4 flex items-center gap-3">
                                        @if($item['image'])
                                            <img src="/images/{{ $item['image'] }}" class="w-14 h-14 object-contain bg-gray-900 rounded">
                                        @else
                                            <div class="w-14 h-14 bg-gray-700 rounded flex items-center justify-center text-gray-500 text-xs">No img</div>
                                        @endif
                                        <span>{{ $item['name'] }}</span>
                                    </td>

                                    <!-- Unit price -->
                                    <td class="py-4">₱{{ number_format($item['price'], 2) }}</td>

                                    <!-- Quantity update -->
                                    <td class="py-4">
                                        <form action="/cart/{{ $id }}" method="POST" class="flex items-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                                class="w-16 text-center text-sm text-gray-900 bg-white rounded border border-gray-500 px-1 py-1">
                                            <button type="submit"
                                                class="text-xs bg-gray-600 hover:bg-gray-500 text-white px-2 py-1 rounded">
                                                Update
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Subtotal -->
                                    <td class="py-4">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>

                                    <!-- Remove -->
                                    <td class="py-4">
                                        <form action="/cart/{{ $id }}/remove" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Total + Actions -->
                    <div class="mt-6 flex items-center justify-between">
                        <form action="{{ route('cart.clear') }}" method="POST"
                            data-confirm="Clear all items from your cart?"
                            data-danger="true">
                            @csrf
                            <button type="submit"
                                class="text-sm text-red-400 hover:text-red-300 underline">
                                Clear Cart
                            </button>
                        </form>

                        <div class="flex items-center gap-4">
                            <span class="text-lg font-bold text-white">
                                Total: ₱{{ number_format($total, 2) }}
                            </span>
                            <a href="{{ route('dashboard') }}"
                                class="text-sm text-gray-400 hover:text-gray-300 underline">
                                Continue Shopping
                            </a>

                            {{-- Regular Place Order (all roles) --}}
                            <form action="{{ route('orders.store') }}" method="POST"
                                data-confirm="Place this order for yourself?">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2 rounded">
                                    Place Order
                                </button>
                            </form>

                            {{-- Staff/Admin: Order on behalf of a customer --}}
                            @if(auth()->check() && in_array(auth()->user()->role, ['staff', 'admin']))
                                <button type="button" onclick="document.getElementById('staff-order-panel').classList.toggle('hidden')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded">
                                    Order for Customer
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Staff email panel --}}
                    @if(auth()->check() && in_array(auth()->user()->role, ['staff', 'admin']))
                        <div id="staff-order-panel" class="hidden mt-4 p-4 border border-blue-500 rounded-lg bg-gray-900">
                            <p class="text-sm text-blue-300 mb-3 font-medium">Place this order on behalf of a customer</p>
                            <form action="{{ route('orders.store') }}" method="POST"
                                data-confirm="Place this order on behalf of the customer?">
                                @csrf
                                <div class="flex items-center gap-3">
                                    <input type="email" name="customer_email"
                                        value="{{ old('customer_email') }}"
                                        placeholder="Enter customer email..."
                                        required
                                        class="flex-1 text-sm rounded border border-gray-600 bg-gray-700 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded whitespace-nowrap">
                                        Confirm Order
                                    </button>
                                </div>
                                @error('customer_email')
                                    <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </form>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
