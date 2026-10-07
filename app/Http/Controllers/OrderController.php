<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Order::with('items', 'customer')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        if ($user->role === 'customer') {
            $query->where('customer_id', $user->id);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $actor = auth()->user();

        if (in_array($actor->role, ['staff', 'admin']) && $request->filled('customer_email')) {
            $request->validate([
                'customer_email' => 'required|email|exists:users,email',
            ]);

            $customer = \App\Models\User::where('email', $request->customer_email)
                ->where('role', 'customer')
                ->first();

            if (!$customer) {
                return redirect()->route('cart.index')
                    ->with('error', 'No customer account found with that email.');
            }

            $customerId = $customer->id;
        } else {
            $customerId = $actor->id;
        }

        try {
            DB::transaction(function () use ($cart, $request, $customerId, $actor) {
                $totalAmount = 0;
                $orderLines = [];

                foreach ($cart as $cartItem) {
                    $item = Item::lockForUpdate()->findOrFail($cartItem['item_id']);

                    if ($item->status !== 'active') {
                        throw new \Exception("'{$item->name}' is no longer available.");
                    }

                    if ($item->quantity < $cartItem['quantity']) {
                        throw new \Exception("Not enough stock for '{$item->name}'. Only {$item->quantity} left.");
                    }

                    $subtotal = $item->price * $cartItem['quantity'];
                    $totalAmount += $subtotal;

                    $orderLines[] = [
                        'item' => $item,
                        'quantity' => $cartItem['quantity'],
                        'price' => $item->price,
                        'subtotal' => $subtotal,
                    ];
                }

                $order = Order::create([
                    'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                    'customer_id' => $customerId,
                    'created_by' => $actor->id,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'notes' => $request->input('notes'),
                ]);

                foreach ($orderLines as $line) {
                    $order->items()->create([
                        'item_id' => $line['item']->id,
                        'quantity' => $line['quantity'],
                        'unit_price' => $line['price'],
                        'subtotal' => $line['subtotal'],
                    ]);
                    $line['item']->decrement('quantity', $line['quantity']);
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        session()->forget('cart');

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        if (auth()->user()->role === 'customer' && $order->customer_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.item', 'customer');

        return view('orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        if (auth()->user()->role === 'customer' && $order->customer_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $line) {
                $line->item->increment('quantity', $line->quantity);
            }
            $order->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Order cancelled and stock restored.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,completed,cancelled',
        ]);

        $new = $request->status;
        $current = $order->status;

        if (in_array($current, ['cancelled', 'completed'])) {
            return back()->with('error', "A {$current} order cannot be changed.");
        }

        $flow = ['pending', 'confirmed', 'processing', 'completed'];

        if ($new !== 'cancelled') {
            $currentIndex = array_search($current, $flow);
            $newIndex = array_search($new, $flow);

            if ($newIndex <= $currentIndex) {
                return back()->with('error', "Cannot move order backwards from '{$current}' to '{$new}'.");
            }
        }

        if ($new === 'cancelled') {
            DB::transaction(function () use ($order, $new) {
                foreach ($order->items as $line) {
                    $line->item->increment('quantity', $line->quantity);
                }
                $order->update(['status' => $new]);
            });
        } else {
            $order->update(['status' => $new]);
        }

        return back()->with('success', "Order status updated to {$new}.");
    }
}
