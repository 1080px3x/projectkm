@extends('layouts.app')

@section('title', 'Daftar Komik — SHINIGAMI')

@section('content')
<div class="wrap pt-8">
    <h1 class="text-2xl font-black">Daftar Komik</h1>
    <p class="mt-1 text-sm text-muted">{{ $comics->total() }} judul ditemukan{{ $q ? ' untuk "'.$q.'"' : '' }}.</p>

    <form method="GET" action="{{ route('comics.index') }}" class="mt-5 flex flex-col sm:flex-row gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul komik..." class="field flex-1">
        <select name="type" class="field sm:w-44" style="appearance:none">
            <option value="">Semua tipe</option>
            @foreach (['project' => 'Project', 'manhwa' => 'Manhwa', 'manhua' => 'Manhua', 'manga' => 'Manga'] as $key => $label)
                <option value="{{ $key }}" @selected($type === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-gold">Terapkan</button>
        @if ($q || $type)
            <a href="{{ route('comics.index') }}" class="btn btn-ghost">Reset</a>
        @endif
    </form>

    @if ($comics->isEmpty())
        <p class="py-16 text-center text-sm text-muted">Tidak ada komik yang cocok.</p>
    @else
        <div class="grid-comics mt-6">
            @foreach ($comics as $comic)
                <x-comic-card :comic="$comic" />
            @endforeach
        </div>
        <div class="mt-8">{{ $comics->links() }}</div>
    @endif
</div>
@endsection
