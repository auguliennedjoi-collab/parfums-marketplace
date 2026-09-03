<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">

    <h2 style="color: #4f46e5;">Mise à jour de votre commande</h2>

    <p>Bonjour {{ $order->user->name }},</p>

    <p>Le statut de votre commande <strong>#{{ $order->id }}</strong> a changé :</p>

    <p style="font-size: 18px; font-weight: bold; color: #4f46e5;">
        {{ ucfirst($order->status) }}
    </p>

    <p><strong>Total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</p>
    <p><strong>Adresse de livraison :</strong> {{ $order->shipping_address }}</p>

    <p style="margin-top: 30px; color: #6b7280; font-size: 13px;">
        Merci de votre confiance.
    </p>

</body>
</html>