<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — SHINIGAMI</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-ink text-white">
<div class="flex min-h-screen">
    <aside class="hidden md:flex w-56 shrink-0 flex-col border-r border-line bg-panel p-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 mb-6">
            <span class="text-sm font-black tracking-[.12em]">SHINIGAMI</span>
            <span class="badge-gold">ADMIN</span>
        </a>
        <nav class="space-y-1 text-sm">
            @foreach ([
                ['admin.dashboard', 'Dashboard', 'admin.dashboard'],
                ['admin.comics.index', 'Komik', 'admin.comics.*'],
                ['admin.genres.index', 'Genre', 'admin.genres.*'],
                ['admin.threads.index', 'Thread', 'admin.threads.*'],
            ] as [$route, $label, $pattern])
                <a href="{{ route($route) }}"
                   class="block rounded-lg px-3 py-2 font-medium {{ request()->routeIs($pattern) ? 'bg-gold text-ink' : 'text-muted hover:bg-panel-2 hover:text-white' }}">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="mt-auto pt-4 border-t border-line text-xs text-muted">
            <p class="truncate">{{ auth()->user()->name }}</p>
            <p class="truncate">{{ auth()->user()->email }}</p>
            <div class="mt-2 flex gap-2">
                <a href="{{ route('home') }}" class="hover:text-white">Situs</a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button class="hover:text-white">Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex-1 min-w-0">
        <header class="flex items-center justify-between gap-3 border-b border-line px-4 md:px-6 h-14">
            <div class="flex items-center gap-3">
                <span class="md:hidden text-sm font-black tracking-[.12em]">S1 ADMIN</span>
                <span class="hidden md:inline text-sm text-muted">@yield('title', 'Admin')</span>
            </div>
            <div class="flex items-center gap-2 text-xs">
                @foreach ([['admin.comics.index', 'Komik'], ['admin.genres.index', 'Genre'], ['admin.threads.index', 'Thread']] as [$route, $label])
                    <a href="{{ route($route) }}" class="md:hidden rounded-md bg-panel px-2 py-1.5 text-muted">{{ $label }}</a>
                @endforeach
                <form action="{{ route('admin.logout') }}" method="POST" class="md:hidden">
                    @csrf
                    <button class="rounded-md bg-panel px-2 py-1.5 text-muted">Keluar</button>
                </form>
            </div>
        </header>

        <main class="p-4 md:p-6">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-gold/40 bg-gold/10 px-4 py-2.5 text-sm text-gold">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-2.5 text-sm text-red-300">
                    {{ $errors->first() }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
