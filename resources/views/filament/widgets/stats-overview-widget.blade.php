<x-filament-widgets::widget>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;padding:0.5rem;">

        {{-- ── Articles ── --}}
        <div style="position:relative;overflow:hidden;border-radius:1rem;background:linear-gradient(135deg,#10b981,#0d9488);padding:1.25rem;color:#fff;box-shadow:0 4px 16px rgba(16,185,129,0.35);">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                 style="position:absolute;right:-1rem;top:-1rem;width:7rem;height:7rem;opacity:.12;">
                <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75-6.75a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd"/>
                <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z"/>
            </svg>

            <p style="font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.8;margin:0 0 .25rem;">Articles</p>
            <p style="font-size:2.5rem;font-weight:900;line-height:1;margin:0 0 .2rem;">{{ number_format($totalContent) }}</p>
            <p style="font-size:.8rem;opacity:.8;margin:0 0 1rem;">Total content</p>

            @if($totalContent > 0)
            <div style="margin-bottom:.75rem;">
                <div style="display:flex;justify-content:space-between;font-size:.7rem;opacity:.8;margin-bottom:.3rem;">
                    <span>{{ $published }} published</span>
                    <span>{{ $totalContent > 0 ? round($published / $totalContent * 100) : 0 }}%</span>
                </div>
                <div style="height:5px;border-radius:9999px;background:rgba(255,255,255,.25);">
                    <div style="height:5px;border-radius:9999px;background:#fff;width:{{ $totalContent > 0 ? round($published / $totalContent * 100) : 0 }}%;"></div>
                </div>
            </div>
            @endif

            <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                <span style="background:rgba(255,255,255,.2);border-radius:9999px;padding:.2rem .65rem;font-size:.7rem;font-weight:600;">{{ $featured }} featured</span>
                <span style="background:rgba(255,255,255,.2);border-radius:9999px;padding:.2rem .65rem;font-size:.7rem;font-weight:600;">{{ $archived }} archived</span>
                <span style="background:rgba(255,255,255,.2);border-radius:9999px;padding:.2rem .65rem;font-size:.7rem;font-weight:600;">{{ $draft }} draft</span>
            </div>
        </div>

        {{-- ── Total Views ── --}}
        <div style="position:relative;overflow:hidden;border-radius:1rem;background:linear-gradient(135deg,#3b82f6,#4f46e5);padding:1.25rem;color:#fff;box-shadow:0 4px 16px rgba(59,130,246,0.35);">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                 style="position:absolute;right:-1rem;top:-1rem;width:7rem;height:7rem;opacity:.12;">
                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd"/>
            </svg>

            <p style="font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.8;margin:0 0 .25rem;">Total Views</p>
            <p style="font-size:2.5rem;font-weight:900;line-height:1;margin:0 0 .2rem;">{{ number_format($totalViews) }}</p>
            <p style="font-size:.8rem;opacity:.8;margin:0 0 1rem;">Across all articles</p>

            @if($published > 0)
            <div style="background:rgba(255,255,255,.15);border-radius:.75rem;padding:.6rem .9rem;">
                <p style="font-size:.7rem;opacity:.8;margin:0 0 .2rem;">Avg. per published article</p>
                <p style="font-size:1.4rem;font-weight:800;margin:0;">{{ number_format($totalViews / $published) }}</p>
            </div>
            @endif
        </div>

        {{-- ── Taxonomy ── --}}
        <div style="position:relative;overflow:hidden;border-radius:1rem;background:linear-gradient(135deg,#f59e0b,#ea580c);padding:1.25rem;color:#fff;box-shadow:0 4px 16px rgba(245,158,11,0.35);">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                 style="position:absolute;right:-1rem;top:-1rem;width:7rem;height:7rem;opacity:.12;">
                <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.12-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd"/>
            </svg>

            <p style="font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.8;margin:0 0 .25rem;">Taxonomy</p>
            <p style="font-size:2.5rem;font-weight:900;line-height:1;margin:0 0 .2rem;">{{ $categories + $classifications }}</p>
            <p style="font-size:.8rem;opacity:.8;margin:0 0 1rem;">Total entries</p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;">
                <div style="background:rgba(255,255,255,.18);border-radius:.75rem;padding:.6rem;text-align:center;">
                    <p style="font-size:1.6rem;font-weight:900;margin:0;line-height:1;">{{ $categories }}</p>
                    <p style="font-size:.7rem;opacity:.85;margin:.2rem 0 0;">Categories</p>
                </div>
                <div style="background:rgba(255,255,255,.18);border-radius:.75rem;padding:.6rem;text-align:center;">
                    <p style="font-size:1.6rem;font-weight:900;margin:0;line-height:1;">{{ $classifications }}</p>
                    <p style="font-size:.7rem;opacity:.85;margin:.2rem 0 0;">Classifications</p>
                </div>
            </div>
        </div>

        {{-- ── Team Members ── --}}
        <div style="position:relative;overflow:hidden;border-radius:1rem;background:linear-gradient(135deg,#8b5cf6,#7c3aed);padding:1.25rem;color:#fff;box-shadow:0 4px 16px rgba(139,92,246,0.35);">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                 style="position:absolute;right:-1rem;top:-1rem;width:7rem;height:7rem;opacity:.12;">
                <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd"/>
                <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z"/>
            </svg>

            <p style="font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.8;margin:0 0 .25rem;">Team Members</p>
            <p style="font-size:2.5rem;font-weight:900;line-height:1;margin:0 0 .2rem;">{{ $teamTotal }}</p>
            <p style="font-size:.8rem;opacity:.8;margin:0 0 1rem;">Total members</p>

            @if($teamTotal > 0)
            <div style="margin-bottom:.75rem;">
                <div style="display:flex;justify-content:space-between;font-size:.7rem;opacity:.8;margin-bottom:.3rem;">
                    <span>{{ $teamVisible }} visible</span>
                    <span>{{ $teamTotal > 0 ? round($teamVisible / $teamTotal * 100) : 0 }}%</span>
                </div>
                <div style="height:5px;border-radius:9999px;background:rgba(255,255,255,.25);">
                    <div style="height:5px;border-radius:9999px;background:#fff;width:{{ $teamTotal > 0 ? round($teamVisible / $teamTotal * 100) : 0 }}%;"></div>
                </div>
            </div>
            @endif

            <div style="background:rgba(255,255,255,.15);border-radius:.75rem;padding:.6rem .9rem;">
                <p style="font-size:.7rem;opacity:.8;margin:0 0 .2rem;">Hidden from site</p>
                <p style="font-size:1.4rem;font-weight:800;margin:0;">{{ $teamTotal - $teamVisible }}</p>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>
