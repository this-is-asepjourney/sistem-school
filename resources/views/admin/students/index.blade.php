<x-layouts.admin :title="'Manajemen Siswa'">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--c-dark);">Daftar Siswa</h2>
            <p class="text-sm font-medium mt-1" style="color:var(--c-muted);">Total {{ $students->total() }} siswa terdaftar dalam sistem</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold cursor-pointer shadow-sm transition-all hover:opacity-80"
                   style="background:white; border:1.5px solid #E8EAED; color:var(--c-medium);"
                   title="Format Kolom: Nama, NIS, Kelas, Tanggal Lahir, Email, Nomor HP">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import CSV
                <input type="file" id="csv_input" class="hidden" accept=".csv">
            </label>
            <a href="{{ route('admin.students.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all hover:opacity-90 hover:shadow-lg"
               style="background:var(--c-dark);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Siswa
            </a>
        </div>
    </div>

    <form id="import-form" method="POST" action="{{ route('admin.students.import') }}" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" name="csv_file" id="csv_file_input">
    </form>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm mb-5" style="border:1px solid #E8EAED;">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..."
                   class="flex-1 rounded-xl px-4 py-2.5 text-sm font-medium"
                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
            <select name="class_id" class="rounded-xl px-4 py-2.5 text-sm font-medium"
                    style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                    onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-xl px-4 py-2.5 text-sm font-medium"
                    style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                    onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
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
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Siswa</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">NIS</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Kelas</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Kelamin</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Status</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr style="border-bottom:1px solid #F4F5F7;"
                            onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold shrink-0 text-white"
                                         style="background:var(--c-dark);">
                                        {{ $student->user?->initials() }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm" style="color:var(--c-dark);">{{ $student->user?->name }}</p>
                                        <p class="text-xs font-medium mt-0.5" style="color:#AAB0AF;">{{ $student->user?->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold" style="color:var(--c-medium);">{{ $student->nis ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold" style="color:var(--c-medium);">{{ $student->class?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm font-medium" style="color:var(--c-medium);">{{ $student->gender === 'L' ? 'Laki-laki' : ($student->gender === 'P' ? 'Perempuan' : '—') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold"
                                      style="{{ $student->status === 'aktif'
                                        ? 'background:#D1FAE5; color:#065F46;'
                                        : ($student->status === 'lulus'
                                            ? 'background:#DBEAFE; color:#1E40AF;'
                                            : 'background:#F4F5F7; color:#AAB0AF;') }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.students.show', $student) }}" title="Detail"
                                       class="transition-opacity hover:opacity-60" style="color:var(--c-muted);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" title="Edit"
                                       class="transition-opacity hover:opacity-60" style="color:var(--c-medium);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
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
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#F4F5F7;">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C9CBCE;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-sm font-semibold mb-2" style="color:var(--c-medium);">Belum ada data siswa ditemukan</p>
                                <a href="{{ route('admin.students.create') }}" class="text-sm font-bold transition-opacity hover:opacity-70" style="color:var(--c-muted);">Daftarkan Siswa Baru →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $students->links() }}</div>

    <script>
        document.getElementById('csv_input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                document.getElementById('csv_file_input').files = dt.files;
                document.getElementById('import-form').submit();
            }
        });
    </script>
</x-layouts.admin>
