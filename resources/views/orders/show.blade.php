<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Order {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">


            <!-- Order Summary -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-gray-200 font-semibold mb-4">Order Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-400">
                    <div>
                        <p><span class="text-gray-300">Order #:</span> <span class="font-mono">{{ $order->order_number }}</span></p>
                        <p class="mt-1"><span class="text-gray-300">Date:</span> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        <p class="mt-1"><span class="text-gray-300">Customer:</span> {{ $order->customer->name }}</p>
                    </div>
                    <div>
                        <p>
                            <span class="text-gray-300">Status:</span>
                            @php
                                $statusColors = [
                                    'pending'    => 'bg-yellow-500',
                                    'confirmed'  => 'bg-blue-500',
                                    'processing' => 'bg-orange-500',
                                    'completed'  => 'bg-green-500',
                                    'cancelled'  => 'bg-red-500',
                                ];
                                $color = $statusColors[$order->status] ?? 'bg-gray-500';
                            @endphp
                            <span class="px-2 py-0.5 rounded text-xs text-white {{ $color }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p class="mt-1"><span class="text-gray-300">Total:</span> ₱{{ number_format($order->total_amount, 2) }}</p>
                        @if($order->notes)
                            <p class="mt-1"><span class="text-gray-300">Notes:</span> {{ $order->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-gray-200 font-semibold mb-4">Items</h3>
                <table class="w-full text-sm text-gray-200">
                    <thead>
                        <tr class="border-b border-gray-600 text-left text-gray-400">
                            <th class="pb-3">Item</th>
                            <th class="pb-3">Unit Price</th>
                            <th class="pb-3">Qty</th>
                            <th class="pb-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $line)
                            <tr class="border-b border-gray-700">
                                <td class="py-3 flex items-center gap-3">
                                    @if($line->item->image)
                                        <img src="/images/{{ $line->item->image }}" class="w-12 h-12 object-contain bg-gray-900 rounded">
                                    @endif
                                    {{ $line->item->name }}
                                </td>
                                <td class="py-3">₱{{ number_format($line->unit_price, 2) }}</td>
                                <td class="py-3">{{ $line->quantity }}</td>
                                <td class="py-3">₱{{ number_format($line->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="pt-4 text-right text-gray-400 font-semibold">Total</td>
                            <td class="pt-4 text-white font-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('orders.index') }}"
                    class="text-sm text-gray-400 hover:text-gray-300 underline">
                    ← Back to Orders
                </a>

                <div class="flex items-center gap-3">
                    {{-- Staff/Admin: update status --}}
                    @if(in_array(auth()->user()->role, ['admin', 'staff']))
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                class="text-sm text-gray-900 bg-white border border-gray-400 rounded px-2 py-1">
                                @foreach(['pending','confirmed','processing','completed','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                                Update Status
                            </button>
                        </form>
                    @endif

                    {{-- Customer: cancel own pending order --}}
                    @if(auth()->user()->role === 'customer' && $order->status === 'pending')
                        <form action="{{ route('orders.destroy', $order) }}" method="POST"
                            data-confirm="Cancel this order? Stock will be restored."
                            data-danger="true">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
