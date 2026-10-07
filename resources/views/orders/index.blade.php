<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">



                <form method="GET" action="{{ route('orders.index') }}" class="flex gap-3 mb-6">
                    <select name="status"
                        class="text-sm rounded border border-gray-500 bg-gray-700 text-white px-3 py-1.5 focus:outline-none">
                        <option value="">All Statuses</option>
                        @foreach(['pending','confirmed','processing','completed','cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-1.5 rounded">
                        Filter
                    </button>
                    <a href="{{ route('orders.index') }}"
                        class="text-sm text-gray-400 hover:text-gray-300 self-center underline">
                        Clear
                    </a>
                </form>

                @if($orders->isEmpty())
                    <p class="text-gray-400 text-center py-12">No orders yet. <a href="{{ route('dashboard') }}" class="text-blue-400 underline">Start shopping</a></p>
                @else
                    <table class="w-full text-sm text-gray-200">
                        <thead>
                            <tr class="border-b border-gray-600 text-left text-gray-400">
                                <th class="pb-3">Order #</th>
                                <th class="pb-3">Date</th>
                                <th class="pb-3">Items</th>
                                <th class="pb-3">Total</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="border-b border-gray-700">
                                    <td class="py-3 font-mono text-xs">{{ $order->order_number }}</td>
                                    <td class="py-3">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="py-3">{{ $order->items->count() }} item(s)</td>
                                    <td class="py-3">₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded text-xs text-white">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="text-xs bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
