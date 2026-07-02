<x-layouts.admin :title="'Manajemen Guru & Staf'">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--c-dark);">Daftar Guru & Staf</h2>
            <p class="text-sm font-medium mt-1" style="color:var(--c-muted);">Total {{ $teachers->total() }} guru & staf terdaftar</p>
        </div>
        <a href="{{ route('admin.teachers.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all hover:opacity-90 hover:shadow-lg"
           style="background:var(--c-dark);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Guru
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm mb-5" style="border:1px solid #E8EAED;">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP guru..."
                   class="flex-1 rounded-xl px-4 py-2.5 text-sm font-medium"
                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
            <select name="status" class="rounded-xl px-4 py-2.5 text-sm font-medium"
                    style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                    onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
            </select>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
                    style="background:var(--c-medium);">Cari</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #E8EAED;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:2px solid #F0F1F3; background:#FAFAFA;">
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Guru / Staf</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">NIP</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Mata Pelajaran</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Status</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr style="border-bottom:1px solid #F4F5F7;"
                            onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold shrink-0 text-white"
                                         style="background:var(--c-muted);">
                                        {{ $teacher->user?->initials() }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm" style="color:var(--c-dark);">{{ $teacher->user?->name }}</p>
                                        <p class="text-xs font-medium mt-0.5" style="color:#AAB0AF;">{{ $teacher->user?->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold" style="color:var(--c-medium);">{{ $teacher->nip ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($teacher->subjects as $subject)
                                        <span class="text-xs px-2 py-1 rounded-lg font-semibold"
                                              style="background:rgba(113,90,90,0.1); color:var(--c-muted);">{{ $subject->name }}</span>
                                    @empty
                                        <span class="text-xs italic" style="color:#C9CBCE;">Belum ada mapel</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold"
                                      style="{{ $teacher->status === 'aktif'
                                        ? 'background:#D1FAE5; color:#065F46;'
                                        : 'background:#FEE2E2; color:#991B1B;' }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.teachers.edit', $teacher) }}" title="Edit"
                                       class="transition-opacity hover:opacity-60" style="color:var(--c-medium);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus" class="transition-opacity hover:opacity-60" style="color:#E53E3E;">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#F4F5F7;">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C9CBCE;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <p class="text-sm font-semibold mb-2" style="color:var(--c-medium);">Belum ada data guru terdaftar</p>
                                <a href="{{ route('admin.teachers.create') }}" class="text-sm font-bold transition-opacity hover:opacity-70" style="color:var(--c-muted);">Tambah Guru Baru →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $teachers->links() }}</div>
</x-layouts.admin>
