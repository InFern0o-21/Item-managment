<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Item') }}
            </h2>
            <a href="/items/{{ $item->id }}"
                class="bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-8">

                <form action="/items/update/{{ $item->id }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}"
                            class="w-full rounded border border-gray-600 bg-gray-700 text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded border border-gray-600 bg-gray-700 text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $item->description) }}</textarea>
                        @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Price & Quantity side by side -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Price (₱)</label>
                            <input type="number" name="price" value="{{ old('price', $item->price) }}" step="0.01" min="0"
                                class="w-full rounded border border-gray-600 bg-gray-700 text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Quantity</label>
                            <input type="number" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="0"
                                class="w-full rounded border border-gray-600 bg-gray-700 text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('quantity') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                        <select name="status"
                            class="w-full rounded border border-gray-600 bg-gray-700 text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active"   {{ old('status', $item->status) === 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $item->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Image</label>
                        @if($item->image)
                            <div class="mb-2">
                                <img src="/images/{{ $item->image }}" alt="{{ $item->name }}"
                                    class="h-24 w-24 object-contain rounded bg-gray-900">
                                <p class="text-xs text-gray-400 mt-1">Current image — upload a new one to replace it.</p>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*"
                            class="text-sm text-gray-300 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-gray-600 file:text-white hover:file:bg-gray-500">
                        @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2 rounded">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
