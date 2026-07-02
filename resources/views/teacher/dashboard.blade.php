<x-layouts.teacher :title="'Dashboard'">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Kelas Diampu</p>
                    <p class="text-3xl font-bold" style="color:var(--c-dark);">{{ $stats['total_classes'] }}</p>
                    <p class="text-xs font-medium mt-1.5" style="color:#AAB0AF;">Kelas aktif</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(55,53,62,0.07);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-dark);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Total Siswa</p>
                    <p class="text-3xl font-bold" style="color:var(--c-dark);">{{ $stats['total_students'] }}</p>
                    <p class="text-xs font-medium mt-1.5" style="color:#AAB0AF;">Di semua kelas</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(113,90,90,0.1);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Tugas Belum Dinilai</p>
                    <p class="text-3xl font-bold" style="color:{{ $stats['pending_submissions'] > 0 ? '#D97706' : 'var(--c-dark)' }};">{{ $stats['pending_submissions'] }}</p>
                    <p class="text-xs font-medium mt-1.5" style="color:#AAB0AF;">Perlu diperiksa</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0" style="background:#FEF3C7;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#D97706;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-muted); letter-spacing:.1em;">Absensi Hari Ini</p>
                    <p class="text-3xl font-bold" style="color:#065F46;">{{ $stats['today_attendance'] }}</p>
                    <p class="text-xs font-medium mt-1.5" style="color:#AAB0AF;">Siswa hadir</p>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0" style="background:#D1FAE5;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#065F46;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Kelas yang Diampu --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <h3 class="text-sm font-bold mb-5" style="color:var(--c-dark);">Kelas yang Diampu</h3>
            <div class="space-y-3">
                @forelse($myClasses as $class)
                    <div class="flex items-center justify-between p-4 rounded-xl transition-colors hover:bg-[#F4F5F7]" style="border:1px solid #F0F1F3;">
                        <div>
                            <p class="font-bold text-sm" style="color:var(--c-dark);">{{ $class->name }}</p>
                            <p class="text-xs font-medium mt-0.5" style="color:#AAB0AF;">{{ $class->students->count() }} siswa terdaftar</p>
                        </div>
                        <a href="{{ route('teacher.attendance.input', ['class_id' => $class->id, 'date' => today()->format('Y-m-d')]) }}"
                           class="text-xs font-bold px-3 py-2 rounded-lg transition-all hover:opacity-80"
                           style="background:var(--c-dark); color:white;">Input Absensi</a>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm font-medium" style="color:#AAB0AF;">Belum ada kelas yang diampu.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Tugas Terbaru --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm" style="border:1px solid #E8EAED;">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold" style="color:var(--c-dark);">Tugas Terbaru</h3>
                <a href="{{ route('teacher.assignments.create') }}"
                   class="text-xs font-bold px-3 py-2 rounded-lg text-white transition-all hover:opacity-80"
                   style="background:var(--c-muted);">+ Buat Tugas</a>
            </div>
            <div class="space-y-3">
                @forelse($recentAssignments as $assignment)
                    <div class="flex items-start justify-between p-4 rounded-xl transition-colors hover:bg-[#F4F5F7]" style="border:1px solid #F0F1F3;">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate" style="color:var(--c-dark);">{{ $assignment->title }}</p>
                            <p class="text-xs font-medium mt-0.5" style="color:#AAB0AF;">{{ $assignment->subject?->name }} · {{ $assignment->class?->name }}</p>
                            <span class="inline-block text-xs font-bold mt-2 px-2 py-0.5 rounded-lg" style="background:rgba(55,53,62,0.07); color:var(--c-medium);">
                                {{ $assignment->submissions_count }} submission
                            </span>
                        </div>
                        @if($assignment->due_date)
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg shrink-0 ml-3 mt-0.5"
                                  style="{{ $assignment->isOverdue()
                                    ? 'background:#FEE2E2; color:#991B1B;'
                                    : 'background:#F4F5F7; color:#AAB0AF;' }}">
                                {{ $assignment->due_date->format('d M') }}
                            </span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm font-medium" style="color:#AAB0AF;">Belum ada tugas dibuat.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.teacher>
