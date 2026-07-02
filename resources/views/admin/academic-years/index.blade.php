{{-- Reusable inline style partial for standard table-page header --}}
<x-layouts.admin :title="'Tahun Ajaran'">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--c-dark);">Tahun Ajaran</h2>
            <p class="text-sm font-medium mt-1" style="color:var(--c-muted);">Kelola tahun ajaran dan semester aktif.</p>
        </div>
        <a href="{{ route('admin.academic-years.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all hover:opacity-90"
           style="background:var(--c-dark);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Tahun Ajaran
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #E8EAED;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:2px solid #F0F1F3; background:#FAFAFA;">
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Nama / Tahun</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Semester</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Periode</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Status</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($years as $year)
                        <tr style="border-bottom:1px solid #F4F5F7;"
                            onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                            <td class="px-6 py-4 font-bold" style="color:var(--c-dark);">{{ $year->name }}</td>
                            <td class="px-6 py-4 text-center font-medium" style="color:var(--c-medium);">{{ $year->semester }}</td>
                            <td class="px-6 py-4 font-medium" style="color:#AAB0AF;">
                                {{ $year->start_date->format('d M Y') }} — {{ $year->end_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($year->is_active)
                                    <span class="text-xs px-2.5 py-1 rounded-lg font-bold" style="background:#D1FAE5; color:#065F46;">Aktif</span>
                                @else
                                    <form method="POST" action="{{ route('admin.academic-years.set-active', $year) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs px-2.5 py-1 rounded-lg font-bold transition-all hover:opacity-80"
                                                style="background:#F4F5F7; color:var(--c-muted); border:1px solid #E8EAED;">Set Aktif</button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.academic-years.edit', $year) }}" class="transition-opacity hover:opacity-60" style="color:var(--c-medium);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.academic-years.destroy', $year) }}" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="transition-opacity hover:opacity-60" style="color:#E53E3E;">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm font-medium" style="color:#AAB0AF;">Belum ada data tahun ajaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $years->links() }}</div>
</x-layouts.admin>
