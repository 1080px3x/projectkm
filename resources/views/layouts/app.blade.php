<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SHINIGAMI — Baca Komik Gratis')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="top" class="min-h-screen flex flex-col">

<header class="sticky top-0 z-50 border-b border-line bg-ink/85 backdrop-blur">
    <div class="wrap flex h-16 items-center gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <span class="text-lg font-black tracking-[.12em]">SHINIGAMI</span>
            <span class="badge-gold mt-0.5">S1</span>
        </a>

        <nav class="hidden md:flex items-center gap-6 ml-4">
            <a href="{{ route('comics.index') }}" class="nav-link {{ request()->routeIs('comics.*') ? 'is-active' : '' }}">Komik</a>
            <a href="{{ route('library.index') }}" class="nav-link {{ request()->routeIs('library.*') ? 'is-active' : '' }}">Pustaka</a>
            <a href="{{ route('community.index') }}" class="nav-link {{ request()->routeIs('community.*') ? 'is-active' : '' }}">Komunitas</a>
            <a href="{{ route('leaderboard') }}" class="nav-link {{ request()->routeIs('leaderboard') ? 'is-active' : '' }}">Leaderboard</a>
        </nav>

        <div class="ml-auto flex items-center gap-1 sm:gap-2">
            <div class="relative" data-notif>
                <button class="icon-btn" data-notif-toggle title="Notifikasi (Alt+T)" aria-label="Notifikasi">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                </button>
                <div class="hidden absolute right-0 mt-2 w-80 surface p-2 shadow-2xl" data-notif-panel>
                    <p class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-muted">Notifikasi</p>
                    @foreach ([['Chapter baru: Shadow Slave Ch.18', '3 jam lalu'], ['Chapter baru: Pick Me Up Ch.21', '6 jam lalu'], ['Balasan baru di Komunitas', '1 hari lalu']] as [$t, $w])
                        <div class="rounded-lg px-3 py-2 hover:bg-panel-2">
                            <p class="text-sm font-medium text-white">{{ $t }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ $w }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <button class="icon-btn" data-search-toggle title="Cari" aria-label="Cari">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            </button>

            @auth
                <div class="relative" data-user>
                    <button class="btn btn-white !py-2 !px-4" data-user-toggle>{{ auth()->user()->name }}</button>
                    <div class="hidden absolute right-0 mt-2 w-48 surface p-2 shadow-2xl" data-user-panel>
                        <a href="{{ route('profile') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-panel-2">Profil</a>
                        <a href="{{ route('settings') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-panel-2">Pengaturan</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="block w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-panel-2">Keluar</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-white hidden sm:inline-flex !py-2 !px-4">Masuk</a>
            @endauth

            <button class="icon-btn md:hidden" data-mobile-toggle aria-label="Menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <div class="hidden border-t border-line md:hidden" data-mobile-menu>
        <div class="wrap flex flex-col py-2">
            <a href="{{ route('comics.index') }}" class="py-2.5 text-sm font-semibold">Komik</a>
            <a href="{{ route('library.index') }}" class="py-2.5 text-sm font-semibold">Pustaka</a>
            <a href="{{ route('community.index') }}" class="py-2.5 text-sm font-semibold">Komunitas</a>
            <a href="{{ route('leaderboard') }}" class="py-2.5 text-sm font-semibold">Leaderboard</a>
            @auth
                <a href="{{ route('profile') }}" class="py-2.5 text-sm font-semibold">Profil</a>
                <a href="{{ route('settings') }}" class="py-2.5 text-sm font-semibold">Pengaturan</a>
            @else
                <a href="{{ route('login') }}" class="py-2.5 text-sm font-semibold">Masuk</a>
                <a href="{{ route('register') }}" class="py-2.5 text-sm font-semibold text-gold">Daftar</a>
            @endauth
        </div>
    </div>

    <div class="hidden border-t border-line bg-ink" data-search-panel>
        <form action="{{ route('comics.index') }}" method="GET" class="wrap py-3 flex items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9a9aa3" stroke-width="1.8" class="shrink-0"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul komik..." class="field !bg-transparent !border-0 !px-1" data-search-input autocomplete="off">
            <button class="btn btn-gold !py-2">Cari</button>
        </form>
    </div>
</header>

<main class="flex-1">
    @if (session('status'))
        <div class="wrap pt-4">
            <div class="rounded-lg border border-gold/40 bg-gold/10 px-4 py-2.5 text-sm text-gold">{{ session('status') }}</div>
        </div>
    @endif
    @yield('content')
</main>

<footer class="border-t border-line mt-16">
    <div class="wrap py-12 grid grid-cols-2 gap-8 md:grid-cols-4">
        <div class="col-span-2 md:col-span-1">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-base font-black tracking-[.12em]">SHINIGAMI</span>
                <span class="badge-gold">S1</span>
            </a>
            <p class="mt-3 text-sm leading-relaxed text-muted max-w-xs">Baca komik manhwa, manhua, manga, dan project orisinal gratis dengan pengalaman terbaik.</p>
        </div>
        <div>
            <p class="text-sm font-bold mb-3">Jelajahi</p>
            <ul class="space-y-2 text-sm text-muted">
                <li><a href="{{ route('comics.index') }}" class="hover:text-white">Komik</a></li>
                <li><a href="{{ route('library.index') }}" class="hover:text-white">Pustaka</a></li>
                <li><a href="{{ route('community.index') }}" class="hover:text-white">Komunitas</a></li>
                <li><a href="{{ route('leaderboard') }}" class="hover:text-white">Leaderboard</a></li>
            </ul>
        </div>
        <div>
            <p class="text-sm font-bold mb-3">Akun</p>
            <ul class="space-y-2 text-sm text-muted">
                @auth
                    <li><a href="{{ route('profile') }}" class="hover:text-white">Profil</a></li>
                    <li><a href="{{ route('settings') }}" class="hover:text-white">Pengaturan</a></li>
                    <li><a href="{{ route('library.index') }}" class="hover:text-white">Pustaka</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="hover:text-white">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white">Daftar</a></li>
                @endauth
            </ul>
        </div>
        <div>
            <p class="text-sm font-bold mb-3">Bantuan</p>
            <ul class="space-y-2 text-sm text-muted">
                <li><a href="{{ route('help') }}" class="hover:text-white">Aura &amp; Badge</a></li>
                <li><a href="{{ route('help') }}" class="hover:text-white">Panduan</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-line">
        <div class="wrap flex flex-col sm:flex-row items-center justify-between gap-3 py-5">
            <p class="text-xs text-muted">© 2026 Shinigami. Semua hak dilindungi.</p>
            <a href="#top" class="btn btn-ghost !py-2 !px-4 text-xs">Kembali ke atas ↑</a>
        </div>
    </div>
</footer>

</body>
</html>
