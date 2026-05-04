<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'The Artisan Supply' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|instrument-serif:400,400i" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#140b0a] text-stone-100 antialiased selection:bg-red-500/40">
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -left-32 top-10 h-96 w-96 rounded-full bg-red-500/25 blur-3xl"></div>
        <div class="absolute right-0 top-1/3 h-[32rem] w-[32rem] rounded-full bg-amber-400/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/2 h-96 w-96 rounded-full bg-purple-600/15 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_0,rgba(20,11,10,.92)_70%)]"></div>
    </div>

    <div class="relative">
        <header class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-8">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-2xl border border-red-300/20 bg-red-500/15 text-xl shadow-lg shadow-red-950/40 transition group-hover:rotate-[-4deg] group-hover:scale-105">⚒️</span>
                <span>
                    <span class="block text-sm font-bold uppercase tracking-[0.28em] text-red-200">The Artisan</span>
                    <span class="block font-serif text-2xl italic text-white">Supply</span>
                </span>
            </a>
            <nav class="flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] p-1 text-sm text-stone-300 backdrop-blur">
                <a href="{{ route('home') }}" class="rounded-full px-4 py-2 hover:bg-white/10 hover:text-white">Shop</a>
                <a href="{{ route('studio') }}" class="rounded-full px-4 py-2 hover:bg-white/10 hover:text-white">Studio</a>
                <a href="{{ route('support') }}" class="rounded-full px-4 py-2 hover:bg-white/10 hover:text-white">Support</a>
            </nav>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="mx-auto mt-24 max-w-7xl px-6 pb-10 text-sm text-stone-500 lg:px-8">
            <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                <p>The Artisan Supply is a fake Laravel-adjacent shop for practical AI demos. No queues were harmed in the making of these products.</p>
            </div>
        </footer>
    </div>
</body>
</html>
