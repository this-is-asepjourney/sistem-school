<x-layouts.admin :title="'Dashboard'">

    {{-- Quick Actions --}}
    <div class="mb-7 flex flex-wrap gap-3">
        <a href="{{ route('admin.students.create') }}"
           class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all duration-200 hover:opacity-90 hover:shadow-lg"
           style="background:var(--c-dark); letter-spacing:.01em;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Tambah Siswa
        </a>
        <a href="{{ route('admin.teachers.create') }}"
           class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all duration-200 hover:shadow"
           style="background:white; border:1.5px solid #E8EAED; color:var(--c-medium);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Tambah Guru
        </a>
        <a href="{{ route('admin.announcements.create') }}"
           class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all duration-200 hover:shadow"
           style="background:white; border:1.5px solid #E8EAED; color:var(--c-medium);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Buat Pengumuman
        </a>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        {{-- Total Siswa --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Total Siswa</p>
                    <p class="text-3xl font-bold" style="color:var(--c-dark);">{{ number_format($stats['total_students']) }}</p>
                    <p class="text-xs font-medium mt-1.5" style="color:var(--c-light); color:#AAB0AF;">Siswa aktif terdaftar</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm"
                     style="background:rgba(55,53,62,0.07);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-dark);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Guru --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Total Guru</p>
                    <p class="text-3xl font-bold" style="color:var(--c-dark);">{{ number_format($stats['total_teachers']) }}</p>
                    <p class="text-xs font-medium mt-1.5" style="color:#AAB0AF;">Pengajar & staf</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm"
                     style="background:rgba(113,90,90,0.1);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-muted);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Kelas --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Total Kelas</p>
                    <p class="text-3xl font-bold" style="color:var(--c-dark);">{{ number_format($stats['total_classes']) }}</p>
                    <p class="text-xs font-medium mt-1.5" style="color:#AAB0AF;">{{ $activeYear?->full_name ?? 'Semua tahun' }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm"
                     style="background:rgba(68,68,78,0.08);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-medium);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- SPP Pending --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">SPP Belum Bayar</p>
                    <p class="text-3xl font-bold" style="color:var(--c-dark);">{{ number_format($stats['pending_payments']) }}</p>
                    <p class="text-xs font-semibold mt-1.5" style="color:#D97706;">Tagihan pending</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm"
                     style="background:#FEF3C7;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#D97706;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Absensi & Siswa Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

        {{-- Absensi Hari Ini --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <h3 class="text-sm font-bold mb-5" style="color:var(--c-dark);">Absensi Hari Ini</h3>
            @php
                $total = $todayAttendance->sum();
                $statuses = [
                    'hadir' => ['label' => 'Hadir',  'color' => '#34D399', 'bg' => '#D1FAE5', 'text' => '#065F46'],
                    'sakit' => ['label' => 'Sakit',  'color' => '#FBBF24', 'bg' => '#FEF3C7', 'text' => '#92400E'],
                    'izin'  => ['label' => 'Izin',   'color' => '#60A5FA', 'bg' => '#DBEAFE', 'text' => '#1E40AF'],
                    'alpa'  => ['label' => 'Alpha',  'color' => '#F87171', 'bg' => '#FEE2E2', 'text' => '#991B1B'],
                ];
            @endphp
            @if($total > 0)
                <div class="space-y-4">
                    @foreach($statuses as $key => $info)
                        @php $count = $todayAttendance[$key] ?? 0; $pct = $total > 0 ? round($count/$total*100) : 0; @endphp
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1.5">
                                <span style="color:{{ $info['text'] }};">{{ $info['label'] }}</span>
                                <span style="color:{{ $info['text'] }};">{{ $count }} &nbsp;<span style="opacity:.6;">({{ $pct }}%)</span></span>
                            </div>
                            <div class="w-full rounded-full h-2" style="background:#F0F1F3;">
                                <div class="h-2 rounded-full transition-all duration-500"
                                     style="width:{{ $pct }}%; background:{{ $info['color'] }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background:#F4F5F7;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C9CBCE;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium" style="color:#AAB0AF;">Belum ada data absensi hari ini</p>
                </div>
            @endif
        </div>

        {{-- Siswa Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold" style="color:var(--c-dark);">Siswa Terbaru Terdaftar</h3>
                <a href="{{ route('admin.students.index') }}"
                   class="text-xs font-bold transition-opacity hover:opacity-70"
                   style="color:var(--c-muted);">Lihat semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentStudents as $student)
                    <div class="flex items-center gap-4 p-3 rounded-xl transition-colors hover:bg-[#F4F5F7]"
                         style="border:1px solid transparent;">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold shrink-0"
                             style="background:rgba(55,53,62,0.08); color:var(--c-dark);">
                            {{ $student->user?->initials() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate" style="color:var(--c-dark);">{{ $student->user?->name }}</p>
                            <p class="text-xs font-medium mt-0.5 truncate" style="color:#AAB0AF;">
                                {{ $student->class?->name ?? 'Belum ada kelas' }} · NIS: {{ $student->nis ?? '-' }}
                            </p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-lg font-bold shrink-0
                            {{ $student->status === 'aktif' ? '' : '' }}"
                              style="{{ $student->status === 'aktif'
                                ? 'background:#D1FAE5; color:#065F46;'
                                : 'background:#F4F5F7; color:#AAB0AF;' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm font-medium" style="color:#AAB0AF;">Belum ada data siswa.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Pembayaran Terbaru --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-sm font-bold" style="color:var(--c-dark);">Pembayaran SPP Terbaru</h3>
            <a href="{{ route('admin.payments.index') }}"
               class="text-xs font-bold transition-opacity hover:opacity-70"
               style="color:var(--c-muted);">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1.5px solid #F0F1F3;">
                        <th class="text-left pb-3 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em; padding-left:0;">Siswa</th>
                        <th class="text-left pb-3 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Jenis</th>
                        <th class="text-left pb-3 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Periode</th>
                        <th class="text-right pb-3 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Jumlah</th>
                        <th class="text-center pb-3 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em; padding-right:0;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                        <tr class="transition-colors" style="border-bottom:1px solid #F4F5F7;"
                            onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                            <td class="py-3.5 font-semibold" style="color:var(--c-dark); padding-left:0;">{{ $payment->student?->user?->name }}</td>
                            <td class="py-3.5 font-medium" style="color:var(--c-medium);">{{ $payment->type }}</td>
                            <td class="py-3.5 font-medium" style="color:#AAB0AF;">{{ $payment->period ?? '-' }}</td>
                            <td class="py-3.5 text-right font-bold" style="color:var(--c-dark);">{{ $payment->formatted_amount }}</td>
                            <td class="py-3.5 text-center" style="padding-right:0;">
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold"
                                      style="{{ $payment->status === 'paid'
                                        ? 'background:#D1FAE5; color:#065F46;'
                                        : ($payment->status === 'overdue'
                                            ? 'background:#FEE2E2; color:#991B1B;'
                                            : 'background:#FEF3C7; color:#92400E;') }}">
                                    {{ $payment->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-sm font-medium" style="color:#AAB0AF;">
                                Belum ada data pembayaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.admin>
