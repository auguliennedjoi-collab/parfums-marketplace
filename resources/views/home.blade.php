<x-guest-layout>
    <div class="min-h-screen bg-gray-50">

        {{-- Barre de navigation --}}
        <nav class="bg-sky-500 border-b border-sky-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-white">
                        Parfums & Accessoires
                    </a>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('products.index') }}" class="text-sm text-sky-50 hover:text-white">
                            Catalogue
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm text-sky-50 hover:text-white">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-sky-50 hover:text-white">
                                Se connecter
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-white text-sky-600 text-sm font-semibold rounded-md hover:bg-sky-50">
                                S'inscrire
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Bannière --}}
        <div class="bg-gradient-to-r from-sky-500 via-sky-600 to-indigo-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
                <h1 class="text-4xl sm:text-5xl font-bold mb-4">
                    Parfums & Accessoires, faits pour vous
                </h1>
                <p class="text-lg text-sky-100 mb-8 max-w-2xl mx-auto">
                    Découvrez une sélection de parfums et accessoires de qualité, proposés par des vendeurs locaux de confiance.
                </p>
                <a href="{{ route('products.index') }}" class="inline-block px-8 py-3 bg-white text-sky-600 font-semibold rounded-md hover:bg-sky-50">
                    Voir le catalogue
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            {{-- Catégories --}}
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Catégories</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition border border-transparent hover:border-sky-300">
                            <p class="font-semibold text-gray-900">{{ $category->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $category->children->count() }} sous-catégories</p>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Produits mis en avant --}}
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Nouveautés</h2>
                    <a href="{{ route('products.index') }}" class="text-sm text-sky-600 hover:underline">
                        Voir tout →
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredProducts as $product)
                        <a href="{{ route('products.show', $product) }}" class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden border border-transparent hover:border-sky-300">
                            <div class="h-48 bg-gray-100 flex items-center justify-center">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                @else
                                    <span class="text-gray-400 text-sm">Pas d'image</span>
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-gray-500 mb-1">{{ $product->vendor->boutique_name }}</p>
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                                <p class="text-sky-600 font-bold">{{ number_format($product->price, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>