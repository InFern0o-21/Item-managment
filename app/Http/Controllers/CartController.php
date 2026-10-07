<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // View cart
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('cart.index', compact('cart', 'total'));
    }

    // Add item to cart
    public function add(Request $request)
    {
        $request->validate([
            'item_id'  => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::findOrFail($request->item_id);
        $cart = session()->get('cart', []);

        $id = $item->id;

        if (isset($cart[$id])) {
            // Item already in cart — add to existing quantity
            $cart[$id]['quantity'] += $request->quantity;
        } else {
            $cart[$id] = [
                'item_id'  => $item->id,
                'name'     => $item->name,
                'price'    => $item->price,
                'image'    => $item->image,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message'    => $item->name . ' added to cart!',
            'cart_count' => count($cart),
        ]);
    }

    // Update quantity of a cart item
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    // Remove a single item from cart
    public function remove($itemId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$itemId]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    // Clear entire cart
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
