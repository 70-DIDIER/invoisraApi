<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $document->type === 'invoice' ? 'FACTURE' : 'DEVIS' }} {{ $document->number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .company-info { text-align: right; }
        .company-logo { max-height: 80px; }
        .title { text-align: center; font-size: 22px; font-weight: bold; margin: 20px 0; text-transform: uppercase; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px 8px; }
        .info-table td:first-child { font-weight: bold; width: 120px; }
        table.items { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table.items th { background: #f5f5f5; padding: 8px; text-align: left; font-weight: bold; }
        table.items td { padding: 8px; border-bottom: 1px solid #eee; }
        table.items td:last-child, table.items th:last-child { text-align: right; }
        .totals { margin-left: auto; width: 300px; }
        .totals td { padding: 4px 8px; }
        .totals .grand-total { font-size: 16px; font-weight: bold; }
        .notes { margin-top: 30px; padding: 10px; background: #f9f9f9; border-left: 3px solid #ccc; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; padding: 10px 0; border-top: 1px solid #eee; }
        .signatures { margin-top: 40px; display: flex; justify-content: space-between; }
        .signatures img { max-height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            @if ($document->company->logo)
                <img src="{{ public_path('storage/' . str_replace('/storage/', '', $document->company->logo)) }}" alt="Logo" class="company-logo">
            @endif
        </div>
        <div class="company-info">
            <strong>{{ $document->company->name }}</strong><br>
            {{ $document->company->address }}<br>
            Tél: {{ $document->company->phone }}<br>
            @if ($document->company->email)
                {{ $document->company->email }}<br>
            @endif
            Gérant: {{ $document->company->manager_name }}
        </div>
    </div>

    <div class="title">{{ $document->type === 'invoice' ? 'FACTURE' : 'DEVIS' }}</div>
    <div style="text-align:center; font-size:14px; color:#666;">N° {{ $document->number }}</div>

    <table class="info-table">
        <tr><td>Client</td><td><strong>{{ $document->client->name }}</strong></td></tr>
        @if ($document->client->phone)
            <tr><td>Téléphone</td><td>{{ $document->client->phone }}</td></tr>
        @endif
        @if ($document->client->address)
            <tr><td>Adresse</td><td>{{ $document->client->address }}</td></tr>
        @endif
        <tr><td>Date d'émission</td><td>{{ $document->issue_date->format('d/m/Y') }}</td></tr>
        @if ($document->valid_until)
            <tr><td>Valable jusqu'au</td><td>{{ $document->valid_until->format('d/m/Y') }}</td></tr>
        @endif
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($document->items as $item)
                <tr>
                    <td>{{ $item->designation }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                    <td>{{ number_format($item->total_price, 2, ',', ' ') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Sous-total</td><td>{{ number_format($document->subtotal ?: $document->items->sum('total_price'), 2, ',', ' ') }} €</td></tr>
        @if ($document->labor_cost > 0)
            <tr><td>Main-d'œuvre</td><td>{{ number_format($document->labor_cost, 2, ',', ' ') }} €</td></tr>
        @endif
        @if ($document->transport_cost > 0)
            <tr><td>Transport</td><td>{{ number_format($document->transport_cost, 2, ',', ' ') }} €</td></tr>
        @endif
        @if ($document->other_cost > 0)
            <tr><td>Autres frais</td><td>{{ number_format($document->other_cost, 2, ',', ' ') }} €</td></tr>
        @endif
        <tr class="grand-total">
            <td><strong>TOTAL TTC</strong></td>
            <td><strong>{{ number_format($document->total, 2, ',', ' ') }} €</strong></td>
        </tr>
    </table>

    @if ($document->total_in_words)
        <p><em>Arrêté la présente {{ $document->type === 'invoice' ? 'facture' : 'devis' }} à la somme de {{ $document->total_in_words }}.</em></p>
    @endif

    @if ($document->notes)
        <div class="notes">
            <strong>Notes :</strong><br>
            {{ nl2br($document->notes) }}
        </div>
    @endif

    <div class="signatures">
        @if ($document->company->signature)
            <div>
                <strong>Signature du gérant</strong><br>
                <img src="{{ public_path('storage/' . str_replace('/storage/', '', $document->company->signature)) }}" alt="Signature">
            </div>
        @endif
        @if ($document->company->stamp)
            <div>
                <img src="{{ public_path('storage/' . str_replace('/storage/', '', $document->company->stamp)) }}" alt="Tampon">
            </div>
        @endif
    </div>

    <div class="footer">
        {{ $document->company->name }} - {{ $document->company->address }} - Tél: {{ $document->company->phone }}
    </div>
</body>
</html>
