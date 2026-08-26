<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catalogue — Parfums & Accessoires
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Barre de recherche --}}
            <form method="GET" action="{{ route('products.index') }}" class="mb-6 flex gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Rechercher un produit..."
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Rechercher
                </button>
            </form>

            <div class="flex flex-col md:flex-row gap-6">

                {{-- Filtres par catégorie --}}
                <aside class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Catégories</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('products.index') }}" class="text-sm {{ !request('category') ? 'font-bold text-indigo-600' : 'text-gray-600 hover:text-indigo-600' }}">
                                    Toutes les catégories
                                </a>
                            </li>
                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="text-sm {{ request('category') == $category->id ? 'font-bold text-indigo-600' : 'text-gray-600 hover:text-indigo-600' }}">
                                        {{ $category->name }}
                                    </a>
                                    @if ($category->children->isNotEmpty())
                                        <ul class="ml-4 mt-1 space-y-1">
                                            @foreach ($category->children as $child)
                                                <li>
                                                    <a href="{{ route('products.index', ['category' => $child->id]) }}" class="text-sm {{ request('category') == $child->id ? 'font-bold text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                                                        {{ $child->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>

                {{-- Grille de produits --}}
                <div class="flex-1">
                    @if ($products->isEmpty())
                        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                            Aucun produit trouvé.
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                <a href="{{ route('products.show', $product) }}" class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                                    <div class="h-48 bg-gray-100 flex items-center justify-center">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        @else
                                            <span class="text-gray-400 text-sm">Pas d'image</span>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">{{ $product->vendor->boutique_name }}</p>
                                        <h3 class="font-semibold text-gray-800 mb-1">{{ $product->name }}</h3>
                                        <p class="text-indigo-600 font-bold">{{ number_format($product->price, 0, ',', ' ') }} FCFA</p>
                                        @if ($product->reviews->isNotEmpty())
                                            <p class="text-xs text-yellow-500 mt-1">★ {{ $product->averageRating() }} ({{ $product->reviews->count() }} avis)</p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>