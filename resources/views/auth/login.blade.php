<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Invoiça</title>
    @fonts
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
            @layer properties{@supports (((-webkit-hyphens:none)) and (not (margin-trim:inline))) or ((-moz-orient:inline) and (not (color:rgb(from red r g b)))){*,:before,:after,::backdrop{--tw-translate-x:0;--tw-translate-y:0;--tw-translate-z:0;--tw-rotate-x:initial;--tw-rotate-y:initial;--tw-rotate-z:initial;--tw-skew-x:initial;--tw-skew-y:initial;--tw-space-x-reverse:0;--tw-border-style:solid;--tw-leading:initial;--tw-font-weight:initial;--tw-tracking:initial;--tw-shadow:0 0 #0000;--tw-shadow-color:initial;--tw-shadow-alpha:100%;--tw-inset-shadow:0 0 #0000;--tw-inset-shadow-color:initial;--tw-inset-shadow-alpha:100%;--tw-ring-color:initial;--tw-ring-shadow:0 0 #0000;--tw-inset-ring-color:initial;--tw-inset-ring-shadow:0 0 #0000;--tw-ring-inset:initial;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-offset-shadow:0 0 #0000;--tw-blur:initial;--tw-brightness:initial;--tw-contrast:initial;--tw-grayscale:initial;--tw-hue-rotate:initial;--tw-invert:initial;--tw-opacity:initial;--tw-saturate:initial;--tw-sepia:initial;--tw-drop-shadow:initial;--tw-drop-shadow-color:initial;--tw-drop-shadow-alpha:100%;--tw-drop-shadow-size:initial;--tw-duration:initial;--tw-ease:initial;--tw-content:""}}}@layer theme{:root,:host{--font-sans:"Instrument Sans", ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";--font-serif:ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;--font-mono:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;--color-gray-50:oklch(0.985 0.002 247.839);--color-gray-100:oklch(0.967 0.003 264.542);--color-gray-200:oklch(0.928 0.006 264.531);--color-gray-300:oklch(0.872 0.01 258.338);--color-gray-400:oklch(0.707 0.022 261.325);--color-gray-500:oklch(0.551 0.027 264.364);--color-gray-600:oklch(0.446 0.03 256.802);--color-gray-700:oklch(0.373 0.034 259.733);--color-gray-800:oklch(0.278 0.033 256.848);--color-gray-900:oklch(0.21 0.034 264.665);--color-gray-950:oklch(0.13 0.028 261.692);--color-white:#fff;--color-black:#000;--spacing:0.25rem;--text-sm:0.875rem;--text-sm--line-height:1.25rem;--text-base:1rem;--text-base--line-height:1.5rem;--text-lg:1.125rem;--text-lg--line-height:1.5rem;--text-xl:1.25rem;--text-xl--line-height:1.75rem;--text-2xl:1.5rem;--text-2xl--line-height:1.75rem;--font-weight-medium:500;--font-weight-semibold:600;--font-weight-bold:700;--font-weight-extrabold:800;--tracking-wider:0.05em;--radius-lg:0.5rem;--radius-xl:0.75rem;--radius-2xl:1rem;--shadow-sm:0 1px 3px 0 rgb(0 0 0 / 0.1),0 1px 2px -1px rgb(0 0 0 / 0.1);--shadow-md:0 4px 6px -1px rgb(0 0 0 / 0.1),0 2px 4px -2px rgb(0 0 0 / 0.1);--shadow-lg:0 10px 15px -3px rgb(0 0 0 / 0.1),0 4px 6px -4px rgb(0 0 0 / 0.1)}
        </style>
    @endif
    <style>
        :root { --primary: #0E7D36; --primary-dark: #0B5C2E; --primary-light: #C8E6D0; --primary-lighter: #E5F5EA; --bg: #F7F8F7; --text-primary: #1A1A1A; --text-secondary: #6B7280; --text-muted: #9CA3AF; --border: #E7EBE8; --danger: #D14343; }
        body { background-color: var(--bg); font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo & Branding --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Invoiça" class="h-16 w-auto mx-auto mb-4">
            <h1 class="text-3xl font-extrabold tracking-wider text-[var(--primary)]">Invoiça</h1>
            <p class="text-sm text-[var(--text-secondary)] mt-1">Vos devis et factures en quelques clics.</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[var(--border)] p-8">
            <h2 class="text-xl font-bold text-[var(--text-primary)] mb-6">Connexion</h2>

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-[var(--text-primary)] mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--border)] bg-white text-sm text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[var(--text-primary)] mb-1.5">Mot de passe</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--border)] bg-white text-sm text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent transition-all">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember"
                            class="rounded border-[var(--border)] text-[var(--primary)] focus:ring-[var(--primary)]">
                        <span class="text-sm text-[var(--text-secondary)]">Se souvenir de moi</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-2.5 rounded-lg bg-[var(--primary)] text-white text-sm font-semibold hover:bg-[var(--primary-dark)] transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:ring-offset-2">
                    Se connecter
                </button>
            </form>

            <p class="text-center mt-6 text-sm text-[var(--text-muted)]">
                Administration Invoiça
            </p>
        </div>
    </div>

</body>
</html>
