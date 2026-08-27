<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cartItems = auth()->user()->cartItems()->with('product.vendor')->get();

        $total = $cartItems->sum(fn (CartItem $item) => $item->subtotal());

        return view('cart.index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $product->stock],
        ]);

        $cartItem = CartItem::firstOrNew([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        $newQuantity = ($cartItem->exists ? $cartItem->quantity : 0) + $validated['quantity'];
        $cartItem->quantity = min($newQuantity, $product->stock);
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeOwner($cartItem);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $cartItem->product->stock],
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);

        return redirect()->route('cart.index')->with('success', 'Panier mis à jour.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->authorizeOwner($cartItem);

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produit retiré du panier.');
    }

    private function authorizeOwner(CartItem $cartItem): void
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }
    }
}