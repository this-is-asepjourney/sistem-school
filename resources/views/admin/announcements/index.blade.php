<x-layouts.admin :title="'Pengumuman'">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--c-dark);">Daftar Pengumuman</h2>
            <p class="text-sm font-medium mt-1" style="color:var(--c-muted);">Total {{ $announcements->total() ?? 0 }} pengumuman.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all hover:opacity-90"
           style="background:var(--c-dark);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Buat Pengumuman
        </a>
    </div>

    <div class="space-y-4">
        @forelse($announcements as $announcement)
            <div class="bg-white rounded-2xl p-5 shadow-sm transition-shadow hover:shadow-md" style="border:1px solid #E8EAED;">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-xs px-2.5 py-1 rounded-lg font-bold"
                                  style="{{ $announcement->is_published
                                    ? 'background:#D1FAE5; color:#065F46;'
                                    : 'background:#F4F5F7; color:#AAB0AF;' }}">
                                {{ $announcement->is_published ? 'Dipublikasi' : 'Draft' }}
                            </span>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg" style="background:rgba(113,90,90,0.1); color:var(--c-muted);">
                                Target: {{ ucfirst($announcement->target) }}
                            </span>
                            <span class="text-xs font-medium" style="color:#AAB0AF;">
                                Oleh: {{ $announcement->creator?->name }}
                            </span>
                        </div>
                        <h3 class="text-base font-bold" style="color:var(--c-dark);">{{ $announcement->title }}</h3>
                        <p class="text-sm font-medium mt-2 line-clamp-2" style="color:#AAB0AF;">{{ strip_tags($announcement->content) }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-3 shrink-0">
                        <span class="text-xs font-medium" style="color:#AAB0AF;">{{ $announcement->created_at->format('d M Y, H:i') }}</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}"
                               class="text-xs px-3 py-1.5 rounded-lg font-bold transition-all hover:opacity-80"
                               style="background:#F4F5F7; border:1px solid #E8EAED; color:var(--c-medium);">Edit</a>
                            <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs px-3 py-1.5 rounded-lg font-bold transition-all hover:opacity-80"
                                        style="background:#FEF2F2; border:1px solid #FECACA; color:#991B1B;">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-14 text-center shadow-sm" style="border:1px solid #E8EAED;">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#F4F5F7;">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C9CBCE;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <p class="text-sm font-semibold" style="color:var(--c-medium);">Belum ada pengumuman dipublikasi.</p>
            </div>
        @endforelse
    </div>

    @if(method_exists($announcements, 'links'))
        <div class="mt-6">{{ $announcements->links() }}</div>
    @endif
</x-layouts.admin>
