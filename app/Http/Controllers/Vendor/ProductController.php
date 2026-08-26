<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $vendor = auth()->user()->vendor;

        $products = $vendor->products()->latest()->paginate(10);

        return view('vendor.products.index', [
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        $categories = Category::whereNotNull('parent_id')->get();

        return view('vendor.products.create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $vendor = auth()->user()->vendor;

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $vendor->products()->create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imagePath,
            'status' => 'active',
        ]);

        return redirect()->route('vendor.products.index')->with('success', 'Produit ajouté avec succès.');
    }

    public function edit(Product $product): View
    {
        $this->authorizeVendor($product);

        $categories = Category::whereNotNull('parent_id')->get();

        return view('vendor.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeVendor($product);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('vendor.products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorizeVendor($product);

        $product->delete();

        return redirect()->route('vendor.products.index')->with('success', 'Produit supprimé.');
    }

    private function authorizeVendor(Product $product): void
    {
        if ($product->vendor_id !== auth()->user()->vendor?->id) {
            abort(403);
        }
    }
}