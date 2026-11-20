@extends('admin.layout')

@section('title', 'Komik')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-black">Komik</h1>
    <div class="flex gap-2">
        <form method="GET" class="flex gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul..." class="field !py-2 sm:w-56">
            <button class="btn btn-ghost !py-2">Cari</button>
        </form>
        <a href="{{ route('admin.comics.create') }}" class="btn btn-gold !py-2">+ Tambah</a>
    </div>
</div>

<div class="mt-4 surface overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="text-left text-xs uppercase tracking-wider text-muted border-b border-line">
            <tr>
                <th class="p-3">Judul</th>
                <th class="p-3">Tipe</th>
                <th class="p-3">Rating</th>
                <th class="p-3">Chapter</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-line">
            @forelse ($comics as $comic)
                <tr class="hover:bg-panel-2/60">
                    <td class="p-3">
                        <div class="flex items-center gap-2">
                            <span class="h-8 w-6 shrink-0 rounded overflow-hidden bg-panel-2" style="background: {{ $comic->coverStyle() }}">
                                @if ($url = $comic->coverUrl())
                                    <img src="{{ $url }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </span>
                            <span class="font-semibold">{{ $comic->title }}</span>
                        </div>
                    </td>
                    <td class="p-3 text-muted">{{ ucfirst($comic->type) }}</td>
                    <td class="p-3 text-gold font-semibold">{{ number_format($comic->rating, 1) }}</td>
                    <td class="p-3 text-muted">{{ $comic->chapters_count }}</td>
                    <td class="p-3 text-muted">{{ $comic->status }}</td>
                    <td class="p-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('comics.show', $comic->slug) }}" class="text-xs text-muted hover:text-white">Lihat</a>
                            <a href="{{ route('admin.comics.edit', $comic) }}" class="text-xs font-semibold text-gold hover:underline">Edit</a>
                            <form action="{{ route('admin.comics.destroy', $comic) }}" method="POST" onsubmit="return confirm('Hapus komik ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs text-red-400 hover:text-red-300">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-6 text-center text-muted">Belum ada komik.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $comics->links() }}</div>
@endsection
