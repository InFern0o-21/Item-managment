<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Container with an added gray border to outline your catalog section -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700"
                style="color: white; font-family: 'Figtree', sans-serif;">
                
                <!-- 5-Column Responsive Tailwind Grid layout -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach ($items as $product)
                        <x-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination Component Section -->
                @if ($items->hasPages())
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        {{ $items->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>