@extends('layouts.app')

@section('title', 'Bantuan — SHINIGAMI')

@section('content')
<div class="wrap pt-8 max-w-[760px]">
    <h1 class="text-2xl font-black">Bantuan</h1>
    <p class="mt-1 text-sm text-muted">Aura &amp; Badge dan panduan penggunaan.</p>

    <section class="surface mt-6 p-6">
        <h2 class="text-base font-bold">Aura &amp; Badge</h2>
        <p class="mt-2 text-sm leading-relaxed text-muted">Aura dikumpulkan dengan membaca chapter, berkomentar di komunitas, dan menjaga streak harian. Semakin tinggi aura, semakin tinggi badge yang kamu dapat.</p>
        <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach ([['Newbie', '0–99'], ['Reader', '100–499'], ['Veteran', '500–1499'], ['Shinigami', '1500+']] as [$badge, $range])
                <div class="rounded-lg border border-line bg-panel-2 p-3 text-center">
                    <p class="text-sm font-bold text-gold">{{ $badge }}</p>
                    <p class="text-xs text-muted">{{ $range }} aura</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="surface mt-4 p-6">
        <h2 class="text-base font-bold">Panduan</h2>
        <ol class="mt-3 space-y-3 text-sm text-muted list-decimal list-inside">
            <li>Pilih komik di halaman Komik atau beranda, lalu buka detail.</li>
            <li>Klik salah satu chapter untuk mulai membaca.</li>
            <li>Tekan Bookmark untuk menyimpan komik ke Pustaka.</li>
            <li>Gunakan kolom Cari untuk menemukan judul dengan cepat.</li>
            <li>Bergabung di Komunitas untuk berdiskusi dengan pembaca lain.</li>
        </ol>
    </section>
</div>
@endsection
