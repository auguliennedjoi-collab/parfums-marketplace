<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $hasPurchased = OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($query) {
                $query->where('user_id', auth()->id())
                    ->whereIn('status', ['paid', 'shipped', 'delivered']);
            })
            ->exists();

        if (! $hasPurchased) {
            return back()->with('error', 'Vous devez avoir acheté ce produit pour laisser un avis.');
        }

        $alreadyReviewed = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Vous avez déjà laissé un avis sur ce produit.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Merci pour votre avis !');
    }
}