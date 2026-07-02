<x-layouts.teacher :title="'Manajemen Tugas'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-white">Daftar Tugas</h2>
        <a href="{{ route('teacher.assignments.create') }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Tugas
        </a>
    </div>

    <div class="space-y-3">
        @forelse($assignments as $assignment)
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-4 hover:border-gray-700 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-white">{{ $assignment->title }}</h3>
                        <p class="text-sm text-gray-400 mt-0.5">{{ $assignment->subject?->name }} · {{ $assignment->class?->name }}</p>
                        @if($assignment->description)
                            <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $assignment->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 ml-4 shrink-0">
                        <div class="text-right">
                            @if($assignment->due_date)
                                <p class="text-xs {{ $assignment->isOverdue() ? 'text-red-400' : 'text-gray-400' }}">
                                    {{ $assignment->isOverdue() ? 'Kadaluarsa' : 'Deadline' }}
                                </p>
                                <p class="text-sm font-medium text-white">{{ $assignment->due_date->format('d M Y') }}</p>
                            @endif
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-400">Submission</p>
                            <p class="text-lg font-bold text-indigo-400">{{ $assignment->submissions_count }}</p>
                        </div>
                        <a href="{{ route('teacher.assignments.show', $assignment) }}" class="text-xs px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg transition-colors">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-10 text-center text-gray-500">
                Belum ada tugas. <a href="{{ route('teacher.assignments.create') }}" class="text-green-400 hover:underline">Buat tugas baru</a>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $assignments->links() }}</div>
</x-layouts.teacher>
