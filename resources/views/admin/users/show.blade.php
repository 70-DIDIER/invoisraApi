@extends('admin.layouts.app')

@section('title', "{$user->name} - Utilisateurs - Administration Invoiça")

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-[var(--primary)] hover:underline">&larr; Retour aux utilisateurs</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-[var(--border)] p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-[var(--primary-light)] flex items-center justify-center text-[var(--primary)] font-bold text-xl mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <h2 class="text-lg font-bold text-[var(--text-primary)]">{{ $user->name }}</h2>
                <p class="text-sm text-[var(--text-secondary)]">{{ $user->email }}</p>
                <div class="mt-3">
                    @if($user->is_admin)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[var(--primary-lighter)] text-[var(--primary)]">Super administrateur</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Utilisateur</span>
                    @endif
                </div>
                <div class="mt-4 pt-4 border-t border-[var(--border)] flex justify-around text-center">
                    <div>
                        <p class="text-xl font-extrabold text-[var(--text-primary)]">{{ $user->clients_count }}</p>
                        <p class="text-xs text-[var(--text-muted)]">Clients</p>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-[var(--text-primary)]">{{ $user->documents_count }}</p>
                        <p class="text-xs text-[var(--text-muted)]">Documents</p>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-[var(--text-primary)]">{{ $user->company ? 'Oui' : 'Non' }}</p>
                        <p class="text-xs text-[var(--text-muted)]">Entreprise</p>
                    </div>
                </div>
                <p class="text-xs text-[var(--text-muted)] mt-4">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            @if($user->company)
                <div class="bg-white rounded-xl border border-[var(--border)] p-5">
                    <h3 class="text-base font-bold text-[var(--text-primary)] mb-3">Entreprise</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="text-[var(--text-muted)]">Nom :</span> <span class="font-medium">{{ $user->company->name }}</span></div>
                        <div><span class="text-[var(--text-muted)]">Email :</span> <span class="font-medium">{{ $user->company->email ?? '—' }}</span></div>
                        <div><span class="text-[var(--text-muted)]">Téléphone :</span> <span class="font-medium">{{ $user->company->phone ?? '—' }}</span></div>
                        <div><span class="text-[var(--text-muted)]">SIRET :</span> <span class="font-medium">{{ $user->company->siret ?? '—' }}</span></div>
                        <div class="col-span-2"><span class="text-[var(--text-muted)]">Adresse :</span> <span class="font-medium">{{ $user->company->address ?? '—' }}</span></div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl border border-[var(--border)] p-5">
                <h3 class="text-base font-bold text-[var(--text-primary)] mb-3">Derniers clients</h3>
                @if($user->clients->count() > 0)
                    <div class="space-y-2">
                        @foreach($user->clients as $client)
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm font-medium">{{ $client->name }}</span>
                                <span class="text-xs text-[var(--text-muted)]">{{ $client->phone ?? '—' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-[var(--text-muted)]">Aucun client.</p>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-[var(--border)] p-5">
                <h3 class="text-base font-bold text-[var(--text-primary)] mb-3">Derniers documents</h3>
                @if($user->documents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-[var(--text-muted)] uppercase">
                                    <th class="pb-2 pr-4 font-medium">N°</th>
                                    <th class="pb-2 pr-4 font-medium">Type</th>
                                    <th class="pb-2 pr-4 font-medium">Client</th>
                                    <th class="pb-2 pr-4 font-medium text-right">Montant</th>
                                    <th class="pb-2 pr-4 font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border)]">
                                @foreach($user->documents as $doc)
                                    <tr>
                                        <td class="py-2 pr-4 font-medium">{{ $doc->number }}</td>
                                        <td class="py-2 pr-4">
                                            @if($doc->type === 'invoice')
                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-[#FBF6E3] text-yellow-800">Facture</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Devis</span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4 text-[var(--text-secondary)]">{{ $doc->client?->name ?? '—' }}</td>
                                        <td class="py-2 pr-4 text-right font-medium">{{ number_format($doc->total, 0, ',', ' ') }} F</td>
                                        <td class="py-2 pr-4 text-[var(--text-muted)]">{{ $doc->issue_date?->format('d/m/Y') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-[var(--text-muted)]">Aucun document.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
