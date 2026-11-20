@extends('layouts.app')

@section('title', 'Pengaturan — SHINIGAMI')

@section('content')
<div class="wrap pt-8 max-w-[680px]">
    <h1 class="text-2xl font-black">Pengaturan</h1>
    <p class="mt-1 text-sm text-muted">Kelola data akun dan kata sandi.</p>

    <div class="surface mt-5 p-5">
        <h2 class="text-base font-bold">Data Akun</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="mt-4 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-xs font-semibold text-muted">Nama</label>
                <input name="name" value="{{ old('name', $user->name) }}" required class="field mt-1">
                @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="field mt-1">
                @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <button class="btn btn-gold">Simpan Perubahan</button>
        </form>
    </div>

    <div class="surface mt-4 p-5">
        <h2 class="text-base font-bold">Ubah Kata Sandi</h2>
        <form method="POST" action="{{ route('settings.password') }}" class="mt-4 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-xs font-semibold text-muted">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required class="field mt-1">
                @error('current_password')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Kata Sandi Baru (min. 8 karakter)</label>
                <input type="password" name="password" required class="field mt-1">
                @error('password')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required class="field mt-1">
            </div>
            <button class="btn btn-gold">Simpan Kata Sandi</button>
        </form>
    </div>
</div>
@endsection
