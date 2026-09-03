<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function home(): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();

        $featuredProducts = Product::where('status', 'active')
            ->with(['vendor', 'category', 'reviews'])
            ->latest()
            ->take(8)
            ->get();

        return view('home', [
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
        ]);
    }

    public function index(Request $request): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();

        $query = Product::query()
            ->where('status', 'active')
            ->with(['vendor', 'category', 'reviews']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function show(Product $product): View
    {
        $product->load(['vendor', 'category', 'reviews.user']);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}