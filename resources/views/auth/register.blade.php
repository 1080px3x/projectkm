@extends('layouts.app')

@section('title', 'Daftar — SHINIGAMI')

@section('content')
<div class="wrap pt-10 max-w-[420px]">
    <div class="surface p-6">
        <h1 class="text-xl font-black">Buat Akun</h1>
        <p class="mt-1 text-sm text-muted">Gratis. Simpan pustaka dan riwayat bacamu.</p>

        <form method="POST" action="{{ route('register.store') }}" class="mt-5 space-y-4">
            @csrf
            <div>
                <label class="text-xs font-semibold text-muted">Nama</label>
                <input name="name" value="{{ old('name') }}" required autofocus class="field mt-1">
                @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="field mt-1">
                @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Password (min. 8 karakter)</label>
                <input type="password" name="password" required class="field mt-1">
                @error('password')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Ulangi Password</label>
                <input type="password" name="password_confirmation" required class="field mt-1">
            </div>
            <button class="btn btn-gold w-full">Daftar</button>
        </form>

        <p class="mt-4 text-center text-sm text-muted">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-gold hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection
