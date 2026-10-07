<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Item List') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg" style="color: white; font-family: 'Figtree', sans-serif;">

                <a href="/items/create" style="color: white;">Add New Product</a>
                <table id="items-table" class="display" style="width:100%; color: white;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="/images/{{ $product->image }}" alt="{{ $product->name }}"
                                            style="max-width: 100px; max-height: 100px;">
                                    @else
                                        No image
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->price }}</td>
                                <td>
                                    @if(auth()->user()->role == 'admin')
                                        <a href="/items/{{ $product->id }}" style="margin-right:4px;">
                                            <button style="background-color: green; color: white;">VIEW</button>
                                        </a>
                                        <a href="/items/{{ $product->id }}/edit" style="margin-right:4px;">
                                            <button style="background-color: blue; color: white;">EDIT</button>
                                        </a>
                                        <form action="/items/{{ $product->id }}/delete" method="POST" style="display:inline"
                                            data-confirm="Delete '{{ $product->name }}'? This cannot be undone."
                                            data-danger="true">
                                            @csrf
                                            <button style="background-color: red; color: white;">DELETE</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#items-table', {
                keys: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                columnDefs: [{ orderable: false, targets: 5 }]
            });
        });
    </script>
</x-app-layout>