<x-layouts.student :title="'Daftar Tugas'">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-white">Tugas & Pengumpulan</h2>
    </div>

    <div class="space-y-3">
        @forelse($assignments as $assignment)
            @php $submission = $assignment->submissions->first(); @endphp
            <a href="{{ route('student.assignments.show', $assignment) }}"
               class="block bg-gray-900 rounded-xl border border-gray-800 hover:border-gray-700 p-4 transition-colors group">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            @if($submission)
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $submission->graded_at ? 'bg-green-900/50 text-green-400' : 'bg-blue-900/50 text-blue-400' }}">
                                    {{ $submission->graded_at ? 'Sudah Dinilai' : 'Sudah Dikumpul' }}
                                </span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $assignment->isOverdue() ? 'bg-red-900/50 text-red-400' : 'bg-yellow-900/50 text-yellow-400' }}">
                                    {{ $assignment->isOverdue() ? 'Terlambat' : 'Belum Dikumpul' }}
                                </span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-white group-hover:text-violet-300 transition-colors">{{ $assignment->title }}</h3>
                        <p class="text-sm text-gray-400 mt-0.5">{{ $assignment->subject?->name }} · Nilai maks: {{ $assignment->max_score }}</p>
                    </div>
                    <div class="text-right ml-4 shrink-0">
                        @if($submission?->score !== null)
                            <p class="text-lg font-bold text-green-400">{{ $submission->score }}</p>
                            <p class="text-xs text-gray-500">/ {{ $assignment->max_score }}</p>
                        @elseif($assignment->due_date)
                            <p class="text-xs text-gray-500">Deadline</p>
                            <p class="text-sm {{ $assignment->isOverdue() ? 'text-red-400' : 'text-gray-300' }}">{{ $assignment->due_date->format('d M Y') }}</p>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-10 text-center text-gray-500">
                Tidak ada tugas untuk kelas Anda.
            </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $assignments->links() }}</div>
</x-layouts.student>
