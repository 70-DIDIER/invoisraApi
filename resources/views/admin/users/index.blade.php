@extends('admin.layouts.app')

@section('title', 'Utilisateurs - Administration Invoiça')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Utilisateurs</h1>
            <p class="text-sm text-[var(--text-secondary)] mt-1">Gérer les utilisateurs de l'application.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-[var(--border)] p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Rechercher par nom ou email..." value="{{ request('search') }}"
                    class="w-full px-3 py-2 rounded-lg border border-[var(--border)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
            </div>
            <select name="admin"
                class="px-3 py-2 rounded-lg border border-[var(--border)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
                <option value="">Tous les utilisateurs</option>
                <option value="yes" {{ request('admin') === 'yes' ? 'selected' : '' }}>Administrateurs</option>
                <option value="no" {{ request('admin') === 'no' ? 'selected' : '' }}>Utilisateurs</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg bg-[var(--primary)] text-white text-sm font-medium hover:bg-[var(--primary-dark)] transition-colors">Filtrer</button>
            @if(request()->anyFilled(['search', 'admin']))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:bg-[var(--bg)] transition-colors">Réinitialiser</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-[var(--border)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-[var(--text-muted)] uppercase tracking-wider bg-[var(--bg)]">
                        <th class="px-5 py-3 font-medium">Nom</th>
                        <th class="px-5 py-3 font-medium">Email</th>
                        <th class="px-5 py-3 font-medium">Rôle</th>
                        <th class="px-5 py-3 font-medium text-center">Clients</th>
                        <th class="px-5 py-3 font-medium text-center">Documents</th>
                        <th class="px-5 py-3 font-medium">Entreprise</th>
                        <th class="px-5 py-3 font-medium">Inscrit le</th>
                        <th class="px-5 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($users as $user)
                        <tr class="hover:bg-[var(--bg)]">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[var(--primary-light)] flex items-center justify-center text-[var(--primary)] font-bold text-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="font-medium text-[var(--text-primary)]">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-[var(--text-secondary)]">{{ $user->email }}</td>
                            <td class="px-5 py-3">
                                @if($user->is_admin)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[var(--primary-lighter)] text-[var(--primary)]">Admin</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Utilisateur</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center text-[var(--text-secondary)]">{{ $user->clients_count }}</td>
                            <td class="px-5 py-3 text-center text-[var(--text-secondary)]">{{ $user->documents_count }}</td>
                            <td class="px-5 py-3 text-[var(--text-secondary)]">{{ $user->company_count ? 'Oui' : '—' }}</td>
                            <td class="px-5 py-3 text-[var(--text-muted)]">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-[var(--primary)] hover:bg-[var(--primary-lighter)] transition-colors">
                                        Voir
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-[var(--text-muted)]">Aucun utilisateur trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-3 border-t border-[var(--border)]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
