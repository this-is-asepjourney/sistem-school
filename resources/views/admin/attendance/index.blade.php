<x-layouts.admin :title="'Rekap Absensi'">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--c-dark);">Rekap Absensi</h2>
            <p class="text-sm font-medium mt-1" style="color:var(--c-muted);">Laporan kehadiran siswa berdasarkan filter yang dipilih.</p>
        </div>
        <a href="{{ route('admin.attendance.export', request()->all()) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all hover:opacity-80"
           style="background:white; border:1.5px solid #E8EAED; color:var(--c-medium); box-shadow:0 1px 3px rgba(55,53,62,0.06);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export CSV
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm mb-5" style="border:1px solid #E8EAED;">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="class_id" class="rounded-xl px-4 py-2.5 text-sm font-medium"
                    style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                    onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            <select name="subject_id" class="rounded-xl px-4 py-2.5 text-sm font-medium"
                    style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                    onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                <option value="">Semua Mapel</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="rounded-xl px-4 py-2.5 text-sm font-medium"
                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="rounded-xl px-4 py-2.5 text-sm font-medium"
                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
                    style="background:var(--c-dark);">Terapkan Filter</button>
        </form>
    </div>

    {{-- Summary Cards --}}
    @if(!empty($recap))
        @php
            $statusInfo = [
                'hadir' => ['label' => 'Hadir',  'bg' => '#D1FAE5', 'text' => '#065F46', 'bar' => '#34D399'],
                'sakit' => ['label' => 'Sakit',  'bg' => '#FEF3C7', 'text' => '#92400E', 'bar' => '#FBBF24'],
                'izin'  => ['label' => 'Izin',   'bg' => '#DBEAFE', 'text' => '#1E40AF', 'bar' => '#60A5FA'],
                'alpa'  => ['label' => 'Alpha',  'bg' => '#FEE2E2', 'text' => '#991B1B', 'bar' => '#F87171'],
            ];
            $total = array_sum($recap);
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
            @foreach($statusInfo as $key => $info)
                <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #E8EAED;">
                    <p class="text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">{{ $info['label'] }}</p>
                    <p class="text-3xl font-bold mt-2" style="color:{{ $info['text'] }};">{{ $recap[$key] ?? 0 }}</p>
                    @php $pct = $total > 0 ? round(($recap[$key] ?? 0) / $total * 100) : 0; @endphp
                    <div class="w-full h-1.5 rounded-full mt-3" style="background:#F0F1F3;">
                        <div class="h-1.5 rounded-full" style="width:{{ $pct }}%; background:{{ $info['bar'] }};"></div>
                    </div>
                    <p class="text-xs font-medium mt-1.5" style="color:#AAB0AF;">{{ $pct }}%</p>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #E8EAED;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:2px solid #F0F1F3; background:#FAFAFA;">
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Tanggal</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Nama Siswa</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Kelas</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Mata Pelajaran</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Status</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        <tr style="border-bottom:1px solid #F4F5F7;"
                            onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                            <td class="px-6 py-4 font-medium" style="color:#AAB0AF;">{{ $att->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-bold" style="color:var(--c-dark);">{{ $att->student?->user?->name }}</td>
                            <td class="px-6 py-4 font-medium" style="color:var(--c-medium);">{{ $att->class?->name }}</td>
                            <td class="px-6 py-4 font-medium" style="color:var(--c-medium);">{{ $att->subject?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold"
                                      style="{{ $att->status === 'hadir'
                                        ? 'background:#D1FAE5; color:#065F46;'
                                        : ($att->status === 'sakit'
                                            ? 'background:#FEF3C7; color:#92400E;'
                                            : ($att->status === 'izin'
                                                ? 'background:#DBEAFE; color:#1E40AF;'
                                                : 'background:#FEE2E2; color:#991B1B;')) }}">
                                    {{ $att->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium" style="color:#AAB0AF;">{{ $att->note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm font-medium" style="color:#AAB0AF;">
                                Tidak ada data absensi. Silakan pilih filter di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $attendances->links() }}</div>
</x-layouts.admin>
