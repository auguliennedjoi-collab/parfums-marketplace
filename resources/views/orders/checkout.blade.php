<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Finaliser la commande
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-3">Récapitulatif</h3>
                @foreach ($cartItems as $item)
                    <div class="flex justify-between text-sm py-1">
                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span>{{ number_format($item->subtotal(), 0, ',', ' ') }} FCFA</span>
                    </div>
                @endforeach
                <div class="flex justify-between font-semibold pt-3 mt-3 border-t">
                    <span>Total</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <form method="POST" action="{{ route('orders.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Adresse de livraison</label>
                        <textarea name="shipping_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Quartier, ville, repère...">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="mobile_money" checked>
                                <span class="text-sm">Mobile Money (Kkiapay / FedaPay)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="a_la_livraison">
                                <span class="text-sm">Paiement à la livraison</span>
                            </label>
                        </div>
                        @error('payment_method') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Confirmer la commande
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>