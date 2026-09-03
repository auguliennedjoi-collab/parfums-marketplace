<x-guest-layout>
    <div class="min-h-screen bg-gray-50">

        {{-- Barre de navigation simple --}}
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">
                        Parfums & Accessoires
                    </a>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">
                            Catalogue
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-indigo-600">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600">
                                Se connecter
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                S'inscrire
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Bannière --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
                <h1 class="text-4xl sm:text-5xl font-bold mb-4">
                    Parfums & Accessoires, faits pour vous
                </h1>
                <p class="text-lg text-indigo-100 mb-8 max-w-2xl mx-auto">
                    Découvrez une sélection de parfums et accessoires de qualité, proposés par des vendeurs locaux de confiance.
                </p>
                <a href="{{ route('products.index') }}" class="inline-block px-8 py-3 bg-white text-indigo-600 font-semibold rounded-md hover:bg-gray-100">
                    Voir le catalogue
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            {{-- Catégories --}}
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Catégories</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
                            <p class="font-semibold text-gray-800">{{ $category->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $category->children->count() }} sous-catégories</p>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Produits mis en avant --}}
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Nouveautés</h2>
                    <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 hover:underline">
                        Voir tout →
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredProducts as $product)
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
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>