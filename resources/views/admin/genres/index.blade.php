@extends('admin.layout')

@section('title', 'Genre')

@section('content')
<h1 class="text-xl font-black">Genre</h1>

<form method="POST" action="{{ route('admin.genres.store') }}" class="mt-4 flex gap-2 max-w-md">
    @csrf
    <input name="name" required placeholder="Nama genre baru" class="field flex-1">
    <button class="btn btn-gold">+ Tambah</button>
</form>

<div class="mt-4 surface divide-y divide-line">
    @forelse ($genres as $genre)
        <div class="flex items-center justify-between gap-3 px-4 py-2.5">
            <div>
                <p class="text-sm font-semibold">{{ $genre->name }}</p>
                <p class="text-[11px] text-muted">{{ $genre->slug }}</p>
            </div>
            <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" onsubmit="return confirm('Hapus genre?')">
                @csrf
                @method('DELETE')
                <button class="text-xs text-red-400 hover:text-red-300">Hapus</button>
            </form>
        </div>
    @empty
        <p class="p-6 text-center text-sm text-muted">Belum ada genre.</p>
    @endforelse
</div>

<div class="mt-6">{{ $genres->links() }}</div>
@endsection
