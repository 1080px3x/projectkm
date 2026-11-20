@extends('layouts.app')

@section('title', 'Komunitas — SHINIGAMI')

@section('content')
<div class="wrap pt-8">
    <h1 class="text-2xl font-black">Komunitas</h1>
    <p class="mt-1 text-sm text-muted">Diskusi, rekomendasi, dan obrolan seputar komik.</p>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-2.5 text-sm text-red-300">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('community.store') }}" class="surface mt-5 p-4 space-y-3">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <input name="title" value="{{ old('title') }}" placeholder="Judul thread" class="field" required maxlength="150">
            <input name="author_name" value="{{ old('author_name') }}" placeholder="Nama kamu" class="field" required maxlength="60">
        </div>
        <textarea name="body" rows="3" placeholder="Tulis sesuatu..." class="field resize-none" required maxlength="2000">{{ old('body') }}</textarea>
        <div class="flex justify-end">
            <button class="btn btn-gold">Buat Thread</button>
        </div>
    </form>

    <div class="mt-6 space-y-3">
        @foreach ($threads as $thread)
            <article class="surface p-4">
                <h2 class="text-sm font-bold">{{ $thread->title }}</h2>
                <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $thread->body }}</p>
                <p class="mt-2 text-xs text-muted">
                    oleh <span class="text-white/80 font-medium">{{ $thread->author_name }}</span> · {{ $thread->created_at->diffForHumans() }}
                </p>
            </article>
        @endforeach
    </div>

    <div class="mt-8">{{ $threads->links() }}</div>
</div>
@endsection
