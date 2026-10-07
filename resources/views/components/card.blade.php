<div class="bg-gray-800 text-white rounded-lg overflow-hidden shadow flex flex-col border border-gray-600">
    @if($product->image)
        <img src="/images/{{ $product->image }}" alt="{{ $product->name }}"
            class="w-full object-contain bg-gray-900" style="height: 180px; padding: 10px;">
    @else
        <div class="w-full bg-gray-700 flex items-center justify-center" style="height: 180px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-500" style="width:64px;height:64px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M16 3H8l-2 4h12l-2-4z" />
                <circle cx="12" cy="13" r="3" stroke-width="1" />
            </svg>
        </div>
    @endif

    <div class="p-3 flex flex-col flex-1">
        <h5 class="font-semibold text-sm text-white mb-1">{{ $product->name }}</h5>
        <p class="text-gray-400 text-xs mb-2 flex-1">{{ $product->description }}</p>
        <div class="flex items-center justify-between mb-2">
            <p class="text-green-400 text-sm font-bold">₱{{ number_format($product->price, 2) }}</p>
            <p class="text-xs {{ $product->quantity > 0 ? 'text-gray-400' : 'text-red-400' }}">
                Stock: {{ $product->quantity }}
            </p>
        </div>
        <div class="flex items-center gap-2 mt-auto">
            <!-- Quantity +/- control -->
            <div class="flex items-center rounded overflow-hidden border border-gray-500">
                <button type="button" onclick="decreaseQuantity({{ $product->id }})"
                    class="px-2 py-2.5 text-sm text-white bg-gray-700 border-r-2 hover:bg-gray-600">&minus;</button>
                <input id="quantity-{{ $product->id }}" value="1" min="1" max="99999"
                    class="w-12 text-center text-white bg-gray-700 border-0 focus:outline-none">
                <button type="button" onclick="increaseQuantity({{ $product->id }})"
                    class="px-2 py-2.5 text-sm text-white bg-gray-700 border-l-2 hover:bg-gray-600">+</button>
            </div>
            <!-- Add to Cart -->
            <button type="button" onclick="addToCart({{ $product->id }})"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-1.5 px-2 rounded">
                Add to Cart
            </button>
        </div>
    </div>
</div>

<script>
function increaseQuantity(id) {
    const input = document.getElementById('quantity-' + id);
    input.value = parseInt(input.value) + 1;
}

function decreaseQuantity(id) {
    const input = document.getElementById('quantity-' + id);
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function addToCart(id) {
    const qty = parseInt(document.getElementById('quantity-' + id).value);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify({ item_id: id, quantity: qty }),
    })
    .then(res => {
        // Laravel redirects unauthenticated requests — if we ended up on a non-JSON page, go to login
        const contentType = res.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            window.location.href = '/login';
            return;
        }
        return res.json();
    })
    .then(data => {
        if (data) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success',
                title: data.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        }
    })
    .catch(() => {
        window.location.href = '/login';
    });
}
</script>
