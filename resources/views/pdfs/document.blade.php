<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $document->type === 'invoice' ? 'FACTURE' : 'DEVIS' }} {{ $document->number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 24px; }
        .header-logo { max-height: 70px; margin-bottom: 8px; }
        .company-name { font-size: 16px; font-weight: bold; color: #0E7D36; margin-bottom: 4px; }
        .company-details { font-size: 11px; color: #555; line-height: 1.6; }
        .title { text-align: center; font-size: 22px; font-weight: bold; margin: 20px 0; text-transform: uppercase; color: #0E7D36; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px 8px; }
        .info-table td:first-child { font-weight: bold; width: 120px; }
        table.items { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table.items th { background: #0E7D36; color: #fff; padding: 8px; text-align: left; font-weight: bold; font-size: 11px; }
        table.items td { padding: 8px; border-bottom: 1px solid #eee; }
        table.items td:last-child, table.items th:last-child { text-align: right; }
        .totals { margin-left: auto; width: 300px; }
        .totals td { padding: 4px 8px; }
        .totals .grand-total { font-size: 16px; font-weight: bold; }
        .totals .grand-total td { border-top: 2px solid #0E7D36; padding-top: 8px; }
        .notes { margin-top: 30px; padding: 10px; background: #f9f9f9; border-left: 3px solid #0E7D36; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; padding: 10px 0; border-top: 1px solid #eee; }
        .signatures { margin-top: 40px; display: flex; justify-content: space-between; }
        .signatures img { max-height: 60px; }
    </style>
</head>
<body>
    @php
        $regularItems = $document->items->filter(fn($item) => !str_starts_with($item->designation, 'FEE:'));
        $feeItems = $document->items->filter(fn($item) => str_starts_with($item->designation, 'FEE:'));
        $cleanNotes = trim(preg_replace('/___FEES___\[.*?\]/s', '', $document->notes ?? ''));
        $displaySubtotal = $document->subtotal ?: $regularItems->sum('total_price');
    @endphp
    <div class="header">
        @if ($document->company->logo)
            <img src="{{ public_path('storage/' . str_replace('/storage/', '', $document->company->logo)) }}" alt="Logo" class="header-logo">
        @else
            <img src="{{ public_path('images/logo.png') }}" alt="Invoiça" class="header-logo">
        @endif
        <div class="company-name">{{ $document->company->name }}</div>
        <div class="company-details">
            {{ $document->company->address }}<br>
            Tél: {{ $document->company->phone }}
            @if ($document->company->email)
                | {{ $document->company->email }}
            @endif
            @if ($document->company->manager_name)
                <br>Gérant: {{ $document->company->manager_name }}
            @endif
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
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($regularItems as $item)
                <tr>
                    <td>{{ $item->designation }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($item->total_price, 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Sous-total</td><td>{{ number_format($displaySubtotal, 0, ',', ' ') }} FCFA</td></tr>
        @if ($feeItems->isNotEmpty())
            @foreach ($feeItems as $fee)
                <tr>
                    <td>{{ str_replace('FEE:', '', $fee->designation) }}</td>
                    <td>{{ number_format($fee->total_price, 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        @else
            @if ($document->labor_cost > 0)
                <tr><td>Main-d'œuvre</td><td>{{ number_format($document->labor_cost, 0, ',', ' ') }} FCFA</td></tr>
            @endif
            @if ($document->transport_cost > 0)
                <tr><td>Transport</td><td>{{ number_format($document->transport_cost, 0, ',', ' ') }} FCFA</td></tr>
            @endif
            @if ($document->other_cost > 0)
                <tr><td>Autres frais</td><td>{{ number_format($document->other_cost, 0, ',', ' ') }} FCFA</td></tr>
            @endif
        @endif
        <tr class="grand-total">
            <td><strong>TOTAL</strong></td>
            <td><strong>{{ number_format($document->total, 0, ',', ' ') }} FCFA</strong></td>
        </tr>
    </table>

    @if ($document->total_in_words)
        <p><em>Arrêté la présente {{ $document->type === 'invoice' ? 'facture' : 'devis' }} à la somme de {{ $document->total_in_words }}.</em></p>
    @endif

    @if ($cleanNotes)
        <div class="notes">
            <strong>Notes :</strong><br>
            {{ nl2br($cleanNotes) }}
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
        {{ $document->company->name }} - {{ $document->company->address }} - Tél: {{ $document->company->phone }}<br>
        <span style="color:#0E7D36;font-size:9px;font-weight:600;">Made by Invoiça</span>
    </div>
</body>
</html>
