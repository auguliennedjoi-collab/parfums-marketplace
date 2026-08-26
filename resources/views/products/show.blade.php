<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-lg shadow p-6 grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Image --}}
                <div class="h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover rounded-lg">
                    @else
                        <span class="text-gray-400">Pas d'image</span>
                    @endif
                </div>

                {{-- Détails --}}
                <div>
                    <p class="text-sm text-gray-500 mb-2">Vendu par {{ $product->vendor->boutique_name }}</p>
                    <h1 class="text-2xl font-bold text-gray-800 mb-3">{{ $product->name }}</h1>
                    <p class="text-3xl font-bold text-indigo-600 mb-4">{{ number_format($product->price, 0, ',', ' ') }} FCFA</p>

                    @if ($product->reviews->isNotEmpty())
                        <p class="text-yellow-500 mb-4">★ {{ $product->averageRating() }} / 5 ({{ $product->reviews->count() }} avis)</p>
                    @endif

                    <p class="text-gray-600 mb-6">{{ $product->description }}</p>

                    <p class="text-sm text-gray-500 mb-4">
                        Stock disponible : {{ $product->stock }}
                    </p>

                    @auth
                        <form method="POST" action="#" class="flex gap-2">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-md border-gray-300">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Ajouter au panier
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Se connecter pour acheter
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Avis --}}
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Avis clients</h3>
                @forelse ($product->reviews as $review)
                    <div class="border-b py-3">
                        <p class="text-sm font-semibold text-gray-800">{{ $review->user->name }} — ★ {{ $review->rating }}/5</p>
                        <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aucun avis pour le moment.</p>
                @endforelse
            </div>

            {{-- Produits similaires --}}
            @if ($relatedProducts->isNotEmpty())
                <div class="mt-8">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Produits similaires</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $related)
                            <a href="{{ route('products.show', $related) }}" class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                                <div class="h-40 bg-gray-100 flex items-center justify-center">
                                    @if ($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-sm">Pas d'image</span>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h4 class="text-sm font-semibold text-gray-800">{{ $related->name }}</h4>
                                    <p class="text-indigo-600 text-sm font-bold">{{ number_format($related->price, 0, ',', ' ') }} FCFA</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>