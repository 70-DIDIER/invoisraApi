@extends('admin.layouts.app')

@section('title', 'Documents - Administration Invoiça')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Documents</h1>
            <p class="text-sm text-[var(--text-secondary)] mt-1">Tous les devis et factures de l'application.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[var(--border)] p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Rechercher par numéro ou projet..." value="{{ request('search') }}"
                    class="w-full px-3 py-2 rounded-lg border border-[var(--border)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
            </div>
            <select name="type"
                class="px-3 py-2 rounded-lg border border-[var(--border)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
                <option value="">Tous les types</option>
                <option value="quote" {{ request('type') === 'quote' ? 'selected' : '' }}>Devis</option>
                <option value="invoice" {{ request('type') === 'invoice' ? 'selected' : '' }}>Factures</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg bg-[var(--primary)] text-white text-sm font-medium hover:bg-[var(--primary-dark)] transition-colors">Filtrer</button>
            @if(request()->anyFilled(['search', 'type']))
                <a href="{{ route('admin.documents.index') }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:bg-[var(--bg)] transition-colors">Réinitialiser</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl border border-[var(--border)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-[var(--text-muted)] uppercase tracking-wider bg-[var(--bg)]">
                        <th class="px-5 py-3 font-medium">N°</th>
                        <th class="px-5 py-3 font-medium">Type</th>
                        <th class="px-5 py-3 font-medium">Utilisateur</th>
                        <th class="px-5 py-3 font-medium hidden sm:table-cell">Client</th>
                        <th class="px-5 py-3 font-medium hidden md:table-cell">Projet</th>
                        <th class="px-5 py-3 font-medium text-right">Montant</th>
                        <th class="px-5 py-3 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-[var(--bg)]">
                            <td class="px-5 py-3 font-medium text-[var(--text-primary)]">{{ $doc->number }}</td>
                            <td class="px-5 py-3">
                                @if($doc->type === 'invoice')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#FBF6E3] text-yellow-800">Facture</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Devis</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-[var(--text-secondary)]">{{ $doc->user?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-[var(--text-secondary)] hidden sm:table-cell">{{ $doc->client?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-[var(--text-secondary)] hidden md:table-cell max-w-[150px] truncate">{{ $doc->project_name ?? '—' }}</td>
                            <td class="px-5 py-3 text-right font-medium text-[var(--text-primary)]">{{ number_format($doc->total, 0, ',', ' ') }} F</td>
                            <td class="px-5 py-3 text-[var(--text-muted)]">{{ $doc->issue_date?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-[var(--text-muted)]">Aucun document trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($documents->hasPages())
            <div class="px-5 py-3 border-t border-[var(--border)]">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
@endsection
