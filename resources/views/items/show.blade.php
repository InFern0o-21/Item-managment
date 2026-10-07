<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('View Item') }}
            </h2>
            <div class="flex gap-2">
                <a href="/items/{{ $item->id }}/edit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                    Edit
                </a>
                <a href="/items"
                    class="bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">

                <!-- Image -->
                <div class="bg-gray-900 flex items-center justify-center" style="height: 260px;">
                    @if($item->image)
                        <img src="/images/{{ $item->image }}" alt="{{ $item->name }}"
                            class="object-contain h-full w-full" style="padding: 16px;">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-600" style="width:80px;height:80px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 3H8l-2 4h12l-2-4z" />
                            <circle cx="12" cy="13" r="3" stroke-width="1" />
                        </svg>
                    @endif
                </div>

                <!-- Details -->
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Name</p>
                            <p class="text-gray-200 font-semibold text-lg">{{ $item->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
                            <span class="px-2 py-0.5 rounded text-xs text-white {{ $item->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Price</p>
                            <p class="text-green-400 font-bold text-lg">₱{{ number_format($item->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Stock Quantity</p>
                            <p class="text-gray-200 font-semibold text-lg">{{ $item->quantity }}</p>
                        </div>
                    </div>
                    @if($item->description)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Description</p>
                            <p class="text-gray-300 text-sm leading-relaxed">{{ $item->description }}</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
