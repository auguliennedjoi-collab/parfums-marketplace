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

            <div id="payment-success-alert" class="hidden mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                Paiement confirmé avec succès !
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm text-gray-500">Statut</span>
                    <span id="order-status-badge" class="px-3 py-1 text-xs rounded-full {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $order->status === 'paid' ? 'Payée' : ucfirst($order->status) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-1">Adresse de livraison</p>
                <p class="text-sm text-gray-800 mb-4">{{ $order->shipping_address }}</p>
                <p class="text-sm text-gray-500 mb-1">Méthode de paiement</p>
                <p class="text-sm text-gray-800">
                    {{ $order->payment_method === 'mobile_money' ? 'Mobile Money' : 'Paiement à la livraison' }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
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

            @if ($order->payment_method === 'mobile_money' && $order->status === 'pending')
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-sm text-gray-600 mb-4">Cliquez ci-dessous pour payer via Mobile Money.</p>
                    <button id="kkiapay-pay-btn" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Payer {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                    </button>
                </div>
            @endif

        </div>
    </div>

    @if ($order->payment_method === 'mobile_money' && $order->status === 'pending')
        <script>
            document.getElementById('kkiapay-pay-btn').addEventListener('click', function () {
                openKkiapayWidget({
                    amount: {{ (int) $order->total_amount }},
                    api_key: "{{ config('services.kkiapay.public_key') }}",
                    sandbox: {{ config('services.kkiapay.sandbox') ? 'true' : 'false' }},
                    email: "{{ auth()->user()->email }}",
                });
            });

            addSuccessListener(function (response) {
                fetch("{{ route('orders.confirm-payment', $order) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({ transactionId: response.transactionId }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('payment-success-alert').classList.remove('hidden');
                        document.getElementById('order-status-badge').textContent = 'Payée';
                        document.getElementById('order-status-badge').className = 'px-3 py-1 text-xs rounded-full bg-green-100 text-green-700';
                        document.getElementById('kkiapay-pay-btn').closest('div.bg-white').style.display = 'none';
                    } else {
                        alert('La vérification du paiement a échoué. Contactez le support si le montant a été débité.');
                    }
                });
            });
        </script>
    @endif
</x-app-layout>