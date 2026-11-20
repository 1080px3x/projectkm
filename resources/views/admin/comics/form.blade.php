@extends('admin.layout')

@section('title', $comic->exists ? 'Edit Komik' : 'Tambah Komik')

@section('content')
<div class="flex items-center justify-between gap-3">
    <h1 class="text-xl font-black">{{ $comic->exists ? 'Edit Komik' : 'Tambah Komik' }}</h1>
    <a href="{{ route('admin.comics.index') }}" class="text-xs text-muted hover:text-white">← Kembali</a>
</div>

<form method="POST"
      action="{{ $comic->exists ? route('admin.comics.update', $comic) : route('admin.comics.store') }}"
      enctype="multipart/form-data"
      class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
    @csrf
    @if ($comic->exists) @method('PUT') @endif

    <div class="lg:col-span-2 surface p-4 space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-semibold text-muted">Judul</label>
                <input name="title" value="{{ old('title', $comic->title) }}" required class="field mt-1">
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Slug (opsional)</label>
                <input name="slug" value="{{ old('slug', $comic->slug) }}" placeholder="otomatis dari judul" class="field mt-1">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="text-xs font-semibold text-muted">Tipe</label>
                <select name="type" class="field mt-1">
                    @foreach (['project' => 'Project', 'manhwa' => 'Manhwa', 'manhua' => 'Manhua', 'manga' => 'Manga'] as $key => $label)
                        <option value="{{ $key }}" @selected(old('type', $comic->type) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Status</label>
                <select name="status" class="field mt-1">
                    @foreach (['Ongoing', 'Completed', 'Hiatus'] as $label)
                        <option value="{{ $label }}" @selected(old('status', $comic->status) === $label)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-muted">Rating (0–10)</label>
                <input type="number" step="0.1" min="0" max="10" name="rating" value="{{ old('rating', $comic->rating) }}" required class="field mt-1">
            </div>
        </div>

        <div>
            <label class="text-xs font-semibold text-muted">Genre (pisahkan dengan koma)</label>
            <input name="genres" value="{{ old('genres', $comic->genres) }}" required placeholder="Action, Fantasy" class="field mt-1">
        </div>

        <div>
            <label class="text-xs font-semibold text-muted">Sinopsis</label>
            <textarea name="synopsis" rows="5" required class="field mt-1 resize-none">{{ old('synopsis', $comic->synopsis) }}</textarea>
        </div>
    </div>

    <div class="surface p-4 space-y-3">
        <p class="text-sm font-bold">Cover</p>

        <div class="aspect-[2/3] w-full rounded-lg overflow-hidden flex items-end"
             style="background: {{ $comic->coverStyle() ?: '#1b1b1f' }}">
            @if ($url = $comic->coverUrl())
                <img src="{{ $url }}" alt="" class="h-full w-full object-cover">
            @endif
        </div>

        <div>
            <label class="text-xs font-semibold text-muted">Upload gambar (jpg/png/webp, maks 2MB)</label>
            <input type="file" name="cover_file" accept="image/*" class="field mt-1 !py-2 text-xs file:mr-2 file:rounded file:border-0 file:bg-panel-2 file:px-2 file:py-1 file:text-xs file:text-white">
        </div>

        <div>
            <label class="text-xs font-semibold text-muted">atau URL gambar</label>
            <input name="cover_url" value="{{ old('cover_url', $comic->coverIsGradient() ? '' : $comic->cover) }}" placeholder="https://..." class="field mt-1">
        </div>

        <div>
            <label class="text-xs font-semibold text-muted">atau gradien CSS</label>
            <input name="cover_gradient" value="{{ old('cover_gradient', $comic->coverIsGradient() ? $comic->cover : '') }}" placeholder="linear-gradient(135deg,#4c1d95,#be123c)" class="field mt-1">
        </div>

        <p class="text-[11px] text-muted leading-relaxed">Prioritas: upload → URL → gradien. Kosongkan semua untuk memakai cover lama.</p>

        <button class="btn btn-gold w-full">{{ $comic->exists ? 'Simpan Perubahan' : 'Simpan Komik' }}</button>
    </div>
</form>

@if ($comic->exists)
    <section class="mt-6 surface p-4">
        <h2 class="text-sm font-bold mb-3">Chapter ({{ $comic->chapters->count() }})</h2>

        <form method="POST" action="{{ route('admin.chapters.store', $comic) }}" class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-end">
            @csrf
            <div>
                <label class="text-[11px] font-semibold text-muted">Nomor</label>
                <input type="number" name="number" min="1" required class="field mt-1 !py-2">
            </div>
            <div>
                <label class="text-[11px] font-semibold text-muted">Judul (opsional)</label>
                <input name="title" class="field mt-1 !py-2">
            </div>
            <div>
                <label class="text-[11px] font-semibold text-muted">Rilis</label>
                <input type="datetime-local" name="released_at" value="{{ now()->format('Y-m-d\TH:i') }}" required class="field mt-1 !py-2">
            </div>
            <button class="btn btn-gold !py-2">+ Tambah Chapter</button>
        </form>

        <div class="mt-4 divide-y divide-line">
            @forelse ($comic->chapters as $chapter)
                <div class="flex flex-wrap items-center gap-2 py-2">
                    <form method="POST" action="{{ route('admin.chapters.update', $chapter) }}" class="flex flex-1 flex-wrap items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="number" name="number" min="1" value="{{ $chapter->number }}" class="field !py-1.5 !w-20">
                        <input name="title" value="{{ $chapter->title }}" placeholder="Judul" class="field !py-1.5 flex-1 min-w-[120px]">
                        <input type="datetime-local" name="released_at" value="{{ $chapter->released_at->format('Y-m-d\TH:i') }}" class="field !py-1.5">
                        <button class="btn btn-ghost !py-1.5 !px-3 text-xs">Simpan</button>
                    </form>
                    <a href="{{ route('admin.chapters.images', $chapter) }}" class="btn btn-ghost !py-1.5 !px-3 text-xs">Gambar ({{ $chapter->images->count() }})</a>
                    <form method="POST" action="{{ route('admin.chapters.destroy', $chapter) }}" onsubmit="return confirm('Hapus chapter?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs text-red-400 hover:text-red-300">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="py-4 text-sm text-muted">Belum ada chapter.</p>
            @endforelse
        </div>
    </section>
@endif
@endsection
