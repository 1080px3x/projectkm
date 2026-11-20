<x-filament-widgets::widget>
    <div style="position:relative; background:#fff; border-radius:16px; overflow:hidden; border:1px solid #e5e7eb; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
        <div style="height:4px; background:linear-gradient(90deg,#f59e0b,#ea580c,#be123c);"></div>

        <div style="padding:20px 24px 24px 24px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px;">
            <div>
                <p style="color:#ea580c; font-size:11px; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; margin:0;">
                    {{ $date }}
                </p>
                <h2 style="color:#1c1917; font-size:1.5rem; font-weight:800; letter-spacing:-0.02em; margin:6px 0 0 0; line-height:1.3;">
                    Halo, {{ $userName }}
                </h2>
                <p style="color:#6b7280; font-size:0.875rem; margin:4px 0 0 0;">
                    Ringkasan perpustakaan komik hari ini.
                </p>

                <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:14px;">
                    <span style="display:inline-flex; align-items:center; gap:6px; background:#fff7ed; color:#c2410c; font-size:12px; font-weight:600; padding:6px 12px; border-radius:10px;">
                        {{ $comicsToday }} komik ditambahkan hari ini
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:6px; background:#fff7ed; color:#c2410c; font-size:12px; font-weight:600; padding:6px 12px; border-radius:10px;">
                        {{ $chaptersToday }} chapter dirilis hari ini
                    </span>
                </div>
            </div>

            <div style="flex-shrink:0;">
                <a href="{{ $createUrl }}"
                   style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; font-weight:700; font-size:14px; padding:10px 20px; border-radius:12px; text-decoration:none; box-shadow:0 4px 12px rgba(234,88,12,0.3);">
                    + Tambah Komik
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
