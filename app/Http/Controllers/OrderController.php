<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function checkout(): View|RedirectResponse
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $total = $cartItems->sum(fn (CartItem $item) => $item->subtotal());

        return view('orders.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:mobile_money,a_la_livraison'],
        ]);

        $cartItems = auth()->user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error', 'Stock insuffisant pour ' . $item->product->name . '.');
            }
        }

        $order = DB::transaction(function () use ($cartItems, $validated) {
            $total = $cartItems->sum(fn (CartItem $item) => $item->subtotal());

            $order = auth()->user()->orders()->create([
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'vendor_id' => $item->product->vendor_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'subtotal' => $item->subtotal(),
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            auth()->user()->cartItems()->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('success', 'Commande passée avec succès.');
    }

    public function show(Order $order): View
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product', 'items.vendor');

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function index(): View
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

        public function confirmPayment(Request $request, Order $order): \Illuminate\Http\JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'transactionId' => ['required', 'string'],
        ]);

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'x-api-key' => config('services.kkiapay.private_key'),
        ])->post('https://api.kkiapay.me/api/v1/transactions/status', [
            'transactionId' => $validated['transactionId'],
        ]);

        $data = $response->json();

        if (($data['status'] ?? null) === 'SUCCESS') {
            $order->update([
                'status' => 'paid',
                'payment_reference' => $validated['transactionId'],
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 422);
    }
}