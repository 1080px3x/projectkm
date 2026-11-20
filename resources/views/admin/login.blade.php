<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — SHINIGAMI</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-ink text-white grid place-items-center px-4">
    <div class="w-full max-w-sm">
        <div class="flex items-center justify-center gap-2 mb-6">
            <span class="text-lg font-black tracking-[.12em]">SHINIGAMI</span>
            <span class="badge-gold">ADMIN</span>
        </div>

        <form method="POST" action="{{ route('admin.login') }}" class="surface p-6 space-y-4">
            @csrf
            @if ($errors->any())
                <div class="rounded-lg border border-red-500/40 bg-red-500/10 px-3 py-2 text-sm text-red-300">{{ $errors->first() }}</div>
            @endif
            <div>
                <label class="text-xs font-semibold text-muted">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="field mt-1">
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Password</label>
                <input type="password" name="password" required class="field mt-1">
            </div>
            <label class="flex items-center gap-2 text-xs text-muted">
                <input type="checkbox" name="remember" value="1" class="accent-gold"> Ingat saya
            </label>
            <button class="btn btn-gold w-full">Masuk</button>
            <a href="{{ route('home') }}" class="block text-center text-xs text-muted hover:text-white">← Kembali ke situs</a>
        </form>
    </div>
</body>
</html>
