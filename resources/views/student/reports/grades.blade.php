<x-layouts.student :title="'Nilai Saya'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-white">Nilai Saya</h2>
        <a href="{{ route('student.rapor.download', ['semester' => $semester, 'academic_year_id' => $yearId]) }}"
           class="flex items-center gap-2 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Download Rapor PDF
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex gap-3 mb-5">
        <select name="academic_year_id" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-violet-500">
            @foreach($academicYears as $year)
                <option value="{{ $year->id }}" {{ $yearId == $year->id ? 'selected' : '' }}>{{ $year->full_name }}</option>
            @endforeach
        </select>
        <select name="semester" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-violet-500">
            <option value="1" {{ $semester == 1 ? 'selected' : '' }}>Semester 1</option>
            <option value="2" {{ $semester == 2 ? 'selected' : '' }}>Semester 2</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg text-sm transition-colors">Tampilkan</button>
    </form>

    <div class="space-y-3">
        @forelse($grades as $subjectId => $subjectGrades)
            @php $subjectName = $subjectGrades->first()->subject?->name; @endphp
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-4">
                <h3 class="font-semibold text-white mb-3">{{ $subjectName }}</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($subjectGrades as $grade)
                        <div class="bg-gray-800/50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-400 mb-1">{{ $grade->type_label }}</p>
                            <p class="text-xl font-bold {{ $grade->normalized_score >= 75 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $grade->score ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $grade->letter_grade }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-10 text-center text-gray-500">
                Belum ada nilai untuk periode ini.
            </div>
        @endforelse
    </div>
</x-layouts.student>
