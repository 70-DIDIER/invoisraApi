@extends('admin.layouts.app')

@section('title', 'Tableau de bord - Administration Invoiça')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text-primary)]">Tableau de bord</h1>
            <p class="text-sm text-[var(--text-secondary)] mt-1">Vue d'ensemble de l'application Invoiça.</p>
        </div>
        <p class="text-xs text-[var(--text-muted)]">{{ now()->translatedFormat('l d F Y') }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-white rounded-xl border border-[var(--border)] p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-[var(--text-secondary)]">Utilisateurs</span>
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-[var(--text-primary)]">{{ $totalUsers }}</p>
            <p class="text-xs text-[var(--text-muted)] mt-1">dont {{ $totalAdmins }} administrateur(s)</p>
        </div>

        <div class="stat-card bg-white rounded-xl border border-[var(--border)] p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-[var(--text-secondary)]">Entreprises</span>
                <div class="w-10 h-10 rounded-lg bg-[var(--primary-lighter)] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[var(--primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-[var(--text-primary)]">{{ $totalCompanies }}</p>
            <p class="text-xs text-[var(--text-muted)] mt-1">sur {{ $totalUsers }} utilisateurs</p>
        </div>

        <div class="stat-card bg-white rounded-xl border border-[var(--border)] p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-[var(--text-secondary)]">Clients</span>
                <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-[var(--text-primary)]">{{ $totalClients }}</p>
            <p class="text-xs text-[var(--text-muted)] mt-1">dans toute l'application</p>
        </div>

        <div class="stat-card bg-white rounded-xl border border-[var(--border)] p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-[var(--text-secondary)]">Documents</span>
                <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-[var(--text-primary)]">{{ $totalDocuments }}</p>
            <p class="text-xs text-[var(--text-muted)] mt-1">{{ $totalQuotes }} devis · {{ $totalInvoices }} factures</p>
        </div>
    </div>

    {{-- Secondary stats row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-[var(--border)] p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#FBF6E3] flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-[var(--text-secondary)]">Revenu total facturé</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)]">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-[var(--border)] p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-[var(--text-secondary)]">Moy. clients par utilisateur</p>
                <p class="text-2xl font-extrabold text-[var(--text-primary)]">{{ $totalUsers > 0 ? number_format($totalClients / $totalUsers, 1) : 0 }}</p>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-[var(--border)] p-5">
            <h2 class="text-lg font-bold text-[var(--text-primary)] mb-4">Documents ({{ now()->format('Y') }})</h2>
            @if(collect($months)->sum('quotes') + collect($months)->sum('invoices') > 0)
                <div class="flex items-end gap-2 h-48 mt-4">
                    @foreach($months as $data)
                        <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end">
                            <div class="flex gap-0.5 w-full items-end justify-center" style="height: 100%">
                                @php $maxVal = max(collect($months)->max('quotes'), collect($months)->max('invoices'), 1); @endphp
                                @if($data['quotes'] > 0)
                                    <div class="bar-chart-bar w-2.5 bg-blue-500 rounded-t" style="height: {{ ($data['quotes'] / $maxVal) * 100 }}%"></div>
                                @endif
                                @if($data['invoices'] > 0)
                                    <div class="bar-chart-bar w-2.5 bg-yellow-500 rounded-t" style="height: {{ ($data['invoices'] / $maxVal) * 100 }}%"></div>
                                @endif
                                @if($data['quotes'] === 0 && $data['invoices'] === 0)
                                    <div class="w-2.5 h-0.5 bg-gray-200 rounded"></div>
                                @endif
                            </div>
                            <span class="text-xs text-[var(--text-muted)] mt-1">{{ $data['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="flex items-center gap-6 mt-4 text-sm text-[var(--text-secondary)]">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-blue-500"></span> Devis</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-yellow-500"></span> Factures</div>
                </div>
            @else
                <div class="text-center py-8 text-[var(--text-muted)]">
                    <svg class="w-12 h-12 mx-auto mb-3 text-[var(--border)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm">Aucune donnée cette année.</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-[var(--border)] p-5">
            <h2 class="text-lg font-bold text-[var(--text-primary)] mb-4">Nouveaux utilisateurs ({{ now()->format('Y') }})</h2>
            @if(collect($userMonths)->sum('count') > 0)
                <div class="flex items-end gap-2 h-48 mt-4">
                    @foreach($userMonths as $data)
                        <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end">
                            @php $maxUsers = max(collect($userMonths)->max('count'), 1); @endphp
                            <div class="bar-chart-bar w-6 bg-[var(--primary)] rounded-t" style="height: {{ ($data['count'] / $maxUsers) * 100 }}%"></div>
                            <span class="text-xs text-[var(--text-muted)] mt-1">{{ $data['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-[var(--text-muted)]">
                    <svg class="w-12 h-12 mx-auto mb-3 text-[var(--border)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <p class="text-sm">Aucun utilisateur cette année.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Users & Documents --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-[var(--border)] p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-[var(--text-primary)]">Derniers utilisateurs</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-[var(--primary)] hover:underline">Voir tout</a>
            </div>
            @if($recentUsers->count() > 0)
                <div class="space-y-3">
                    @foreach($recentUsers as $u)
                        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-[var(--bg)] transition-colors">
                            <div class="w-9 h-9 rounded-full bg-[var(--primary-light)] flex items-center justify-center text-[var(--primary)] font-bold text-sm">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-[var(--text-primary)] truncate">{{ $u->name }}</p>
                                <p class="text-xs text-[var(--text-muted)] truncate">{{ $u->email }}</p>
                            </div>
                            @if($u->is_admin)
                                <span class="shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-[var(--primary-lighter)] text-[var(--primary)]">Admin</span>
                            @endif
                            <p class="text-xs text-[var(--text-muted)] shrink-0">{{ $u->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-[var(--text-muted)]">
                    <p class="text-sm">Aucun utilisateur.</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-[var(--border)] p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-[var(--text-primary)]">Derniers documents</h2>
                <a href="{{ route('admin.documents.index') }}" class="text-sm text-[var(--primary)] hover:underline">Voir tout</a>
            </div>
            @if($recentDocuments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-[var(--text-muted)] uppercase tracking-wider">
                                <th class="pb-3 pr-4 font-medium">N°</th>
                                <th class="pb-3 pr-4 font-medium">Type</th>
                                <th class="pb-3 pr-4 font-medium hidden sm:table-cell">Utilisateur</th>
                                <th class="pb-3 pr-4 font-medium text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)]">
                            @foreach($recentDocuments as $doc)
                                <tr class="hover:bg-[var(--bg)]">
                                    <td class="py-3 pr-4 font-medium text-[var(--text-primary)]">{{ $doc->number }}</td>
                                    <td class="py-3 pr-4">
                                        @if($doc->type === 'invoice')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#FBF6E3] text-yellow-800">Facture</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Devis</span>
                                        @endif
                                    </td>
                                    <td class="py-3 pr-4 text-[var(--text-secondary)] hidden sm:table-cell">{{ $doc->user?->name ?? '—' }}</td>
                                    <td class="py-3 pr-4 text-right font-medium text-[var(--text-primary)]">{{ number_format($doc->total, 0, ',', ' ') }} F</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-[var(--text-muted)]">
                    <p class="text-sm">Aucun document.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
