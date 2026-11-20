<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <span style="display:flex;align-items:center;gap:8px;font-weight:700;">
                Komik Peringkat Teratas
            </span>
        </x-slot>

        <div style="display:flex;flex-direction:column;gap:2px;">
            @forelse($topComics as $index => $comic)
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:8px 0;{{ ! $loop->last ? 'border-bottom:1px solid #f3f4f6;' : '' }}">
                    <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                        @php
                            $colors = ['#f59e0b', '#9ca3af', '#b45309', '#818cf8', '#a5b4fc'];
                        @endphp
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:9999px;background:{{ $colors[$index] ?? '#d1d5db' }};color:#fff;font-size:11px;font-weight:700;flex-shrink:0;">
                            {{ $index + 1 }}
                        </span>
                        <span style="font-size:13px;font-weight:500;color:#374151;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $comic->title }}
                        </span>
                    </div>
                    <span style="font-size:12px;font-weight:700;color:#6b7280;flex-shrink:0;">
                        {{ number_format($comic->rating, 1) }}
                    </span>
                </div>
            @empty
                <p style="font-size:13px;color:#6b7280;text-align:center;padding:16px 0;">Belum ada komik berating.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
