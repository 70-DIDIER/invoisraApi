@extends('admin.layouts.app')

@section('title', 'Clients - Administration Invoiça')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Clients</h1>
            <p class="text-sm text-[var(--text-secondary)] mt-1">Tous les clients de l'application.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[var(--border)] p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Rechercher par nom, téléphone ou adresse..." value="{{ request('search') }}"
                    class="w-full px-3 py-2 rounded-lg border border-[var(--border)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-[var(--primary)] text-white text-sm font-medium hover:bg-[var(--primary-dark)] transition-colors">Filtrer</button>
            @if(request()->has('search'))
                <a href="{{ route('admin.clients.index') }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:bg-[var(--bg)] transition-colors">Réinitialiser</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl border border-[var(--border)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-[var(--text-muted)] uppercase tracking-wider bg-[var(--bg)]">
                        <th class="px-5 py-3 font-medium">Nom</th>
                        <th class="px-5 py-3 font-medium">Téléphone</th>
                        <th class="px-5 py-3 font-medium hidden md:table-cell">Adresse</th>
                        <th class="px-5 py-3 font-medium">Propriétaire</th>
                        <th class="px-5 py-3 font-medium text-center">Documents</th>
                        <th class="px-5 py-3 font-medium">Créé le</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($clients as $client)
                        <tr class="hover:bg-[var(--bg)]">
                            <td class="px-5 py-3 font-medium text-[var(--text-primary)]">{{ $client->name }}</td>
                            <td class="px-5 py-3 text-[var(--text-secondary)]">{{ $client->phone ?? '—' }}</td>
                            <td class="px-5 py-3 text-[var(--text-secondary)] hidden md:table-cell max-w-[200px] truncate">{{ $client->address ?? '—' }}</td>
                            <td class="px-5 py-3 text-[var(--text-secondary)]">{{ $client->user?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-center text-[var(--text-secondary)]">{{ $client->documents_count }}</td>
                            <td class="px-5 py-3 text-[var(--text-muted)]">{{ $client->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-[var(--text-muted)]">Aucun client trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($clients->hasPages())
            <div class="px-5 py-3 border-t border-[var(--border)]">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
@endsection
