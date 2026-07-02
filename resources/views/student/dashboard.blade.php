<x-layouts.student :title="'Dashboard'">

    {{-- Welcome Banner --}}
    <div class="relative rounded-2xl p-6 mb-7 overflow-hidden shadow-sm"
         style="background:var(--c-dark); border:1px solid rgba(211,218,217,0.1);">
        {{-- Decorative elements --}}
        <div class="absolute top-0 right-0 w-48 h-48 rounded-full opacity-10"
             style="background:var(--c-light); transform:translate(30%, -30%); filter:blur(24px);"></div>
        <div class="absolute bottom-0 left-20 w-32 h-32 rounded-full opacity-10"
             style="background:var(--c-muted); transform:translateY(30%); filter:blur(20px);"></div>

        <div class="relative z-10 flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:rgba(211,218,217,0.6); letter-spacing:.12em;">Selamat Datang Kembali</p>
                <h2 class="text-2xl font-extrabold text-white leading-tight">{{ auth()->user()->name }}</h2>
                <div class="flex items-center flex-wrap gap-3 mt-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold text-white"
                          style="background:rgba(255,255,255,0.15); border:1px solid rgba(211,218,217,0.25);">
                        {{ $student?->class?->name ?? 'Belum ada kelas' }}
                    </span>
                    <span class="text-xs font-medium" style="color:rgba(211,218,217,0.6);">NIS: {{ $student?->nis ?? '—' }}</span>
                </div>
            </div>
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-extrabold text-white shrink-0 shadow-lg"
                 style="background:rgba(113,90,90,0.5); border:1.5px solid rgba(211,218,217,0.2);">
                {{ auth()->user()->initials() }}
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Rata-rata Nilai</p>
                    <p class="text-3xl font-bold" style="color:var(--c-dark);">{{ $stats['avg_grade'] ? number_format($stats['avg_grade'], 1) : '—' }}</p>
                </div>
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(55,53,62,0.07);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-dark);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Kehadiran</p>
                    <p class="text-3xl font-bold" style="color:{{ $stats['attendance_percent'] >= 75 ? '#065F46' : '#991B1B' }};">{{ $stats['attendance_percent'] }}%</p>
                </div>
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0"
                     style="background:{{ $stats['attendance_percent'] >= 75 ? '#D1FAE5' : '#FEE2E2' }};">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color:{{ $stats['attendance_percent'] >= 75 ? '#065F46' : '#991B1B' }};"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Tugas Pending</p>
                    <p class="text-3xl font-bold" style="color:{{ $stats['pending_assignments'] > 0 ? '#D97706' : 'var(--c-dark)' }};">{{ $stats['pending_assignments'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0" style="background:#FEF3C7;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#D97706;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">SPP Belum Bayar</p>
                    <p class="text-3xl font-bold" style="color:{{ $stats['pending_payments'] > 0 ? '#991B1B' : 'var(--c-dark)' }};">{{ $stats['pending_payments'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0" style="background:#FEE2E2;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#991B1B;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Tugas Mendatang --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold" style="color:var(--c-dark);">Tugas Mendatang</h3>
                <a href="{{ route('student.assignments.index') }}" class="text-xs font-bold transition-opacity hover:opacity-70" style="color:var(--c-muted);">Lihat semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($upcomingAssignments as $assignment)
                    <a href="{{ route('student.assignments.show', $assignment) }}"
                       class="flex items-start gap-3 p-4 rounded-xl transition-all hover:bg-[#F4F5F7] group"
                       style="border:1px solid #F0F1F3;">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-colors group-hover:bg-[#37353E]"
                             style="background:#F4F5F7;">
                            <svg class="w-4 h-4 transition-colors group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate" style="color:var(--c-dark);">{{ $assignment->title }}</p>
                            <p class="text-xs font-medium mt-0.5" style="color:#AAB0AF;">{{ $assignment->subject?->name }}</p>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 rounded-lg shrink-0"
                              style="{{ $assignment->isOverdue()
                                ? 'background:#FEE2E2; color:#991B1B;'
                                : 'background:#F4F5F7; color:#AAB0AF;' }}">
                            {{ $assignment->due_date?->format('d M') }}
                        </span>
                    </a>
                @empty
                    <div class="text-center py-10">
                        <p class="text-2xl mb-2">🎉</p>
                        <p class="text-sm font-medium" style="color:#AAB0AF;">Tidak ada tugas mendatang.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Nilai Terbaru --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold" style="color:var(--c-dark);">Nilai Terbaru</h3>
                <a href="{{ route('student.grades') }}" class="text-xs font-bold transition-opacity hover:opacity-70" style="color:var(--c-muted);">Lihat semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentGrades as $grade)
                    <div class="flex items-center justify-between p-4 rounded-xl transition-colors hover:bg-[#F4F5F7]" style="border:1px solid #F0F1F3;">
                        <div>
                            <p class="text-sm font-bold" style="color:var(--c-dark);">{{ $grade->subject?->name }}</p>
                            <p class="text-xs font-medium mt-0.5" style="color:#AAB0AF;">{{ $grade->type_label }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-extrabold" style="color:{{ $grade->normalized_score >= 75 ? '#065F46' : '#991B1B' }};">
                                {{ $grade->score ?? '—' }}
                            </p>
                            <p class="text-xs font-bold uppercase tracking-wider mt-0.5" style="color:#AAB0AF;">{{ $grade->letter_grade }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-sm font-medium" style="color:#AAB0AF;">Belum ada nilai tercatat.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.student>
