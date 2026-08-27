<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Commande #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm text-gray-500">Statut</span>
                    <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-1">Adresse de livraison</p>
                <p class="text-sm text-gray-800 mb-4">{{ $order->shipping_address }}</p>
                <p class="text-sm text-gray-500 mb-1">Méthode de paiement</p>
                <p class="text-sm text-gray-800">
                    {{ $order->payment_method === 'mobile_money' ? 'Mobile Money' : 'Paiement à la livraison' }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Articles</h3>
                @foreach ($order->items as $item)
                    <div class="flex justify-between text-sm py-2 border-b last:border-b-0">
                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span>{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                @endforeach
                <div class="flex justify-between font-semibold pt-3 mt-3 border-t">
                    <span>Total</span>
                    <span>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>