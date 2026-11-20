@extends('layouts.app')

@section('title', 'Masuk — SHINIGAMI')

@section('content')
<div class="wrap pt-10 max-w-[420px]">
    <div class="surface p-6">
        <h1 class="text-xl font-black">Masuk</h1>
        <p class="mt-1 text-sm text-muted">Lanjutkan membaca dan simpan pustakamu.</p>

        <form method="POST" action="{{ route('login.store') }}" class="mt-5 space-y-4">
            @csrf
            <div>
                <label class="text-xs font-semibold text-muted">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="field mt-1">
                @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Password</label>
                <input type="password" name="password" required class="field mt-1">
                @error('password')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <label class="flex items-center gap-2 text-xs text-muted">
                <input type="checkbox" name="remember" value="1" class="accent-gold"> Ingat saya
            </label>
            <button class="btn btn-gold w-full">Masuk</button>
        </form>

        <p class="mt-4 text-center text-sm text-muted">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-gold hover:underline">Daftar</a>
        </p>
    </div>
</div>
@endsection
