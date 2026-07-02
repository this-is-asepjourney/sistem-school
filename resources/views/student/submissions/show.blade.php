<x-layouts.student :title="'Detail Tugas'">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('student.assignments.index') }}" class="flex items-center gap-2 text-sm text-gray-400 hover:text-gray-200 mb-3 w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>

        {{-- Assignment Detail --}}
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-5 mb-4">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h2 class="text-lg font-semibold text-white">{{ $assignment->title }}</h2>
                    <p class="text-sm text-gray-400 mt-0.5">{{ $assignment->subject?->name }} · {{ $assignment->class?->name }}</p>
                </div>
                @if($assignment->due_date)
                    <div class="text-right shrink-0 ml-4">
                        <p class="text-xs text-gray-500">Deadline</p>
                        <p class="text-sm {{ $assignment->isOverdue() ? 'text-red-400' : 'text-gray-300' }}">
                            {{ $assignment->due_date->isoFormat('D MMM Y HH:mm') }}
                        </p>
                    </div>
                @endif
            </div>
            @if($assignment->description)
                <div class="bg-gray-800/50 rounded-lg p-3 text-sm text-gray-300 leading-relaxed">
                    {!! nl2br(e($assignment->description)) !!}
                </div>
            @endif
            @if($assignment->file_path)
                <div class="mt-3">
                    <a href="{{ Storage::url($assignment->file_path) }}" target="_blank"
                       class="flex items-center gap-2 text-sm text-indigo-400 hover:text-indigo-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download Lampiran Tugas
                    </a>
                </div>
            @endif
        </div>

        {{-- Submission Status --}}
        @if($submission)
            <div class="bg-gray-900 rounded-xl border {{ $submission->graded_at ? 'border-green-800' : 'border-indigo-800' }} p-5 mb-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-2 h-2 rounded-full {{ $submission->graded_at ? 'bg-green-400' : 'bg-indigo-400' }}"></div>
                    <h3 class="text-sm font-semibold text-white">
                        {{ $submission->graded_at ? 'Sudah Dinilai' : 'Sudah Dikumpulkan' }}
                    </h3>
                </div>
                @if($submission->graded_at)
                    <div class="flex items-center gap-6">
                        <div>
                            <p class="text-xs text-gray-400">Nilai</p>
                            <p class="text-3xl font-bold {{ $submission->score >= 75 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $submission->score }}
                            </p>
                        </div>
                        @if($submission->feedback)
                            <div class="flex-1">
                                <p class="text-xs text-gray-400 mb-1">Feedback Guru</p>
                                <p class="text-sm text-gray-300">{{ $submission->feedback }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-400">Dikumpulkan pada {{ $submission->submitted_at?->isoFormat('D MMM Y HH:mm') }}</p>
                    @if($submission->note)
                        <p class="text-sm text-gray-500 mt-1">{{ $submission->note }}</p>
                    @endif
                @endif
            </div>
        @endif

        {{-- Upload Form --}}
        @if(!$submission || !$submission->graded_at)
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-white mb-4">
                    {{ $submission ? 'Update Pengumpulan' : 'Kumpulkan Tugas' }}
                </h3>
                <form method="POST" action="{{ route('student.assignments.submit', $assignment) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5">Catatan / Jawaban</label>
                            <textarea name="note" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-violet-500 resize-none" placeholder="Tulis catatan atau jawaban...">{{ $submission?->note }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5">Upload File</label>
                            <input type="file" name="file" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-400 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-gray-700 file:text-gray-200 file:cursor-pointer hover:file:bg-gray-600">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-sm font-medium transition-colors">
                                {{ $submission ? 'Update' : 'Kumpulkan' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-layouts.student>
