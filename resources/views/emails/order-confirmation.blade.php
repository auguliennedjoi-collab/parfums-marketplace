<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">

    <h2 style="color: #4f46e5;">Merci pour votre commande !</h2>

    <p>Bonjour {{ $order->user->name }},</p>

    <p>Votre commande <strong>#{{ $order->id }}</strong> a bien été enregistrée.</p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead>
            <tr style="background-color: #f3f4f6;">
                <th style="text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb;">Produit</th>
                <th style="text-align: right; padding: 8px; border-bottom: 1px solid #e5e7eb;">Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;">{{ $item->product->name }} × {{ $item->quantity }}</td>
                    <td style="text-align: right; padding: 8px; border-bottom: 1px solid #e5e7eb;">{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-size: 18px; font-weight: bold;">
        Total : {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
    </p>

    <p><strong>Adresse de livraison :</strong> {{ $order->shipping_address }}</p>
    <p><strong>Méthode de paiement :</strong> {{ $order->payment_method === 'mobile_money' ? 'Mobile Money' : 'Paiement à la livraison' }}</p>

    <p style="margin-top: 30px; color: #6b7280; font-size: 13px;">
        Merci de votre confiance.
    </p>

</body>
</html>