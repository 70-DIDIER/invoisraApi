<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <p>Bonjour <strong>{{ $document->client->name }}</strong>,</p>

    <p>
        Veuillez trouver ci-joint votre
        <strong>{{ $document->type === 'invoice' ? 'facture' : 'devis' }}</strong>
        n° <strong>{{ $document->number }}</strong>.
    </p>

    @if ($document->notes)
        <p><strong>Notes :</strong><br>{{ nl2br($document->notes) }}</p>
    @endif

    <p>
        Projet : <strong>{{ $document->project_name }}</strong><br>
        Montant total : <strong>{{ number_format($document->total, 0, ',', ' ') }} FCFA</strong>
    </p>

    <p>
        Cordialement,<br>
        <strong>{{ $document->company->manager_name }}</strong><br>
        {{ $document->company->name }}<br>
        {{ $document->company->phone }}
    </p>
</body>
</html>
