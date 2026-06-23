<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration - Invoiça')</title>
    @fonts
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
            @layer properties{@supports (((-webkit-hyphens:none)) and (not (margin-trim:inline))) or ((-moz-orient:inline) and (not (color:rgb(from red r g b)))){*,:before,:after,::backdrop{--tw-translate-x:0;--tw-translate-y:0;--tw-translate-z:0;--tw-rotate-x:initial;--tw-rotate-y:initial;--tw-rotate-z:initial;--tw-skew-x:initial;--tw-skew-y:initial;--tw-space-x-reverse:0;--tw-border-style:solid;--tw-leading:initial;--tw-font-weight:initial;--tw-tracking:initial;--tw-shadow:0 0 #0000;--tw-shadow-color:initial;--tw-shadow-alpha:100%;--tw-inset-shadow:0 0 #0000;--tw-inset-shadow-color:initial;--tw-inset-shadow-alpha:100%;--tw-ring-color:initial;--tw-ring-shadow:0 0 #0000;--tw-inset-ring-color:initial;--tw-inset-ring-shadow:0 0 #0000;--tw-ring-inset:initial;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-offset-shadow:0 0 #0000;--tw-blur:initial;--tw-brightness:initial;--tw-contrast:initial;--tw-grayscale:initial;--tw-hue-rotate:initial;--tw-invert:initial;--tw-opacity:initial;--tw-saturate:initial;--tw-sepia:initial;--tw-drop-shadow:initial;--tw-drop-shadow-color:initial;--tw-drop-shadow-alpha:100%;--tw-drop-shadow-size:initial;--tw-duration:initial;--tw-ease:initial;--tw-content:""}}}@layer theme{:root,:host{--font-sans:"Instrument Sans", ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";--font-serif:ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;--font-mono:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;--color-green-50:oklch(0.982 0.018 155.826);--color-green-100:oklch(0.962 0.033 156.278);--color-green-200:oklch(0.925 0.058 155.988);--color-green-300:oklch(0.871 0.088 156.163);--color-green-400:oklch(0.792 0.119 156.039);--color-green-500:oklch(0.723 0.141 155.479);--color-green-600:oklch(0.627 0.143 153.952);--color-green-700:oklch(0.527 0.132 153.221);--color-green-800:oklch(0.448 0.113 153.271);--color-green-900:oklch(0.393 0.095 152.535);--color-green-950:oklch(0.266 0.065 152.934);--color-gray-50:oklch(0.985 0.002 247.839);--color-gray-100:oklch(0.967 0.003 264.542);--color-gray-200:oklch(0.928 0.006 264.531);--color-gray-300:oklch(0.872 0.01 258.338);--color-gray-400:oklch(0.707 0.022 261.325);--color-gray-500:oklch(0.551 0.027 264.364);--color-gray-600:oklch(0.446 0.03 256.802);--color-gray-700:oklch(0.373 0.034 259.733);--color-gray-800:oklch(0.278 0.033 256.848);--color-gray-900:oklch(0.21 0.034 264.665);--color-gray-950:oklch(0.13 0.028 261.692);--color-white:#fff;--color-black:#000;--spacing:0.25rem;--container-md:28rem;--container-lg:32rem;--container-xl:36rem;--container-2xl:42rem;--container-3xl:48rem;--container-4xl:56rem;--container-5xl:64rem;--container-6xl:72rem;--container-7xl:80rem;--text-xs:0.75rem;--text-xs--line-height:1rem;--text-sm:0.875rem;--text-sm--line-height:1.25rem;--text-base:1rem;--text-base--line-height:1.5rem;--text-lg:1.125rem;--text-lg--line-height:1.5rem;--text-xl:1.25rem;--text-xl--line-height:1.75rem;--text-2xl:1.5rem;--text-2xl--line-height:1.75rem;--text-3xl:1.875rem;--text-3xl--line-height:2.25rem;--font-weight-medium:500;--font-weight-semibold:600;--font-weight-bold:700;--font-weight-extrabold:800;--tracking-wider:0.05em;--radius-md:0.375rem;--radius-lg:0.5rem;--radius-xl:0.75rem;--radius-2xl:1rem;--shadow-sm:0 1px 3px 0 rgb(0 0 0 / 0.1),0 1px 2px -1px rgb(0 0 0 / 0.1);--shadow-md:0 4px 6px -1px rgb(0 0 0 / 0.1),0 2px 4px -2px rgb(0 0 0 / 0.1);--shadow-lg:0 10px 15px -3px rgb(0 0 0 / 0.1),0 4px 6px -4px rgb(0 0 0 / 0.1)}
        </style>
    @endif
    <style>
        :root { --primary: #0E7D36; --primary-dark: #0B5C2E; --primary-light: #C8E6D0; --primary-lighter: #E5F5EA; --bg: #F7F8F7; --text-primary: #1A1A1A; --text-secondary: #6B7280; --text-muted: #9CA3AF; --border: #E7EBE8; --danger: #D14343; --sidebar: #0B5C2E; }
        body { background-color: var(--bg); font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; color: var(--text-primary); }
        .sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; color: rgba(255,255,255,0.75); transition: all 0.15s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-link.active { background: rgba(255,255,255,0.15); color: white; font-weight: 600; }
        .stat-card { transition: all 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -8px rgba(14,125,54,0.15); }
        .bar-chart-bar { transition: height 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
    @stack('styles')
</head>
<body>

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="hidden lg:flex lg:flex-col w-64 bg-[var(--sidebar)] text-white">
            <div class="p-5 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Invoiça" class="h-8 w-auto brightness-0 invert">
                    <span class="text-lg font-extrabold tracking-wider">Invoiça</span>
                </a>
                <p class="text-xs text-white/50 mt-1 ml-1">Administration</p>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Tableau de bord
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    Utilisateurs
                </a>
                <a href="{{ route('admin.clients.index') }}" class="sidebar-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Clients
                </a>
                <a href="{{ route('admin.documents.index') }}" class="sidebar-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Documents
                </a>
            </nav>

            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-white/50 truncate">Super admin</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white/50 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Mobile header --}}
        <div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-[var(--border)] px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Invoiça" class="h-7 w-auto">
                <span class="font-extrabold text-[var(--primary)]">Invoiça</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-[var(--text-secondary)]">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[var(--danger)] text-sm">Déconnexion</button>
                </form>
            </div>
        </div>

        {{-- Mobile bottom nav --}}
        <div class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-[var(--border)] flex items-center justify-around h-14 px-2">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('admin.dashboard') ? 'text-[var(--primary)]' : 'text-[var(--text-muted)]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[10px] font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('admin.users.*') ? 'text-[var(--primary)]' : 'text-[var(--text-muted)]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
                <span class="text-[10px] font-medium">Users</span>
            </a>
            <a href="{{ route('admin.clients.index') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('admin.clients.*') ? 'text-[var(--primary)]' : 'text-[var(--text-muted)]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-[10px] font-medium">Clients</span>
            </a>
            <a href="{{ route('admin.documents.index') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('admin.documents.*') ? 'text-[var(--primary)]' : 'text-[var(--text-muted)]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-[10px] font-medium">Docs</span>
            </a>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden lg:pt-0 pt-14 pb-16 lg:pb-0">
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-6 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700 flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600 flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
