<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mon panier
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if ($cartItems->isEmpty())
                <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                    Votre panier est vide.
                    <div class="mt-4">
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">
                            Voir le catalogue
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix unitaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sous-total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" class="h-12 w-12 object-cover rounded">
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-800">{{ $item->product->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $item->product->vendor->boutique_name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ number_format($item->product->price, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-16 rounded-md border-gray-300 text-sm">
                                            <button type="submit" class="text-xs text-indigo-600 hover:underline">Mettre à jour</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                        {{ number_format($item->subtotal(), 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('cart.destroy', $item) }}" onsubmit="return confirm('Retirer ce produit du panier ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:underline">Retirer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-white rounded-lg shadow p-6 mt-4 flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-800">
                        Total : {{ number_format($total, 0, ',', ' ') }} FCFA
                    </p>
                   <a href="{{ route('orders.checkout') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
    Passer la commande
</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>