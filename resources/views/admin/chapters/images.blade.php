@extends('admin.layout')

@section('title', 'Gambar Chapter')

@section('content')
<div class="flex items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-black">Gambar {{ $chapter->label() }}</h1>
        <p class="text-xs text-muted">{{ $chapter->comic->title }}@if ($chapter->title) — {{ $chapter->title }}@endif</p>
    </div>
    <a href="{{ route('admin.comics.edit', $chapter->comic) }}" class="text-xs text-muted hover:text-white">&larr; Kembali</a>
</div>

<form method="POST" action="{{ route('admin.chapters.images.store', $chapter) }}" enctype="multipart/form-data"
      class="mt-4 surface p-4 flex flex-col sm:flex-row sm:items-end gap-3">
    @csrf
    <div class="flex-1">
        <label class="text-xs font-semibold text-muted">Unggah gambar (bisa pilih banyak, jpg/png/webp, maks 4MB/gambar)</label>
        <input type="file" name="images[]" accept="image/*" multiple required
               class="field mt-1 !py-2 text-xs file:mr-2 file:rounded file:border-0 file:bg-panel-2 file:px-2 file:py-1 file:text-xs file:text-white">
    </div>
    <button class="btn btn-gold">Unggah</button>
</form>

<div class="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
    @forelse ($chapter->images as $image)
        <div class="surface overflow-hidden">
            <img src="{{ $image->url() }}" alt="Halaman {{ $loop->iteration }}" class="w-full aspect-[3/4] object-cover">
            <div class="flex items-center justify-between gap-2 p-2">
                <span class="text-xs text-muted">#{{ $loop->iteration }}</span>
                <form method="POST" action="{{ route('admin.chapter-images.destroy', $image) }}" onsubmit="return confirm('Hapus gambar ini?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-400 hover:text-red-300">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-sm text-muted col-span-full">Belum ada gambar. Unggah di atas.</p>
    @endforelse
</div>
@endsection
