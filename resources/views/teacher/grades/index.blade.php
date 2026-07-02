<x-layouts.teacher :title="'Input Nilai'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-white">Input Nilai</h2>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6 bg-gray-900 rounded-xl border border-gray-800 p-4">
        <select name="class_id" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-green-500">
            <option value="">-- Pilih Kelas --</option>
            @foreach($myClasses as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
            @endforeach
        </select>
        <select name="subject_id" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-green-500">
            <option value="">-- Pilih Mapel --</option>
            @foreach($mySubjects as $subject)
                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
            @endforeach
        </select>
        <select name="semester" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-green-500">
            <option value="1" {{ request('semester', 1) == 1 ? 'selected' : '' }}>Semester 1</option>
            <option value="2" {{ request('semester') == 2 ? 'selected' : '' }}>Semester 2</option>
        </select>
        <select name="type" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-green-500">
            <option value="uh" {{ request('type', 'uh') === 'uh' ? 'selected' : '' }}>Ulangan Harian</option>
            <option value="uts" {{ request('type') === 'uts' ? 'selected' : '' }}>UTS</option>
            <option value="uas" {{ request('type') === 'uas' ? 'selected' : '' }}>UAS</option>
            <option value="tugas" {{ request('type') === 'tugas' ? 'selected' : '' }}>Tugas</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">Tampilkan</button>
    </form>

    @if(request('class_id') && request('subject_id') && $grades->count())
        <form method="POST" action="{{ route('teacher.grades.store') }}">
            @csrf
            <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 border-b border-gray-800 bg-gray-800/50">
                            <th class="text-left px-4 py-3">Nama Siswa</th>
                            <th class="text-center px-4 py-3 w-32">Nilai (0-100)</th>
                            <th class="text-left px-4 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($grades as $index => $grade)
                            <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $grade->student_id }}">
                            <input type="hidden" name="grades[{{ $index }}][subject_id]" value="{{ $grade->subject_id }}">
                            <input type="hidden" name="grades[{{ $index }}][semester]" value="{{ request('semester', 1) }}">
                            <input type="hidden" name="grades[{{ $index }}][type]" value="{{ request('type', 'uh') }}">
                            <tr class="hover:bg-gray-800/30 transition-colors">
                                <td class="px-4 py-3 text-white">{{ $grade->student?->user?->name }}</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="grades[{{ $index }}][score]" value="{{ $grade->score }}"
                                           min="0" max="100" step="0.5"
                                           class="w-full bg-gray-800 border border-gray-700 rounded px-2 py-1 text-sm text-center text-gray-200 focus:outline-none focus:border-green-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="grades[{{ $index }}][note]" value="{{ $grade->note }}"
                                           class="w-full bg-gray-800 border border-gray-700 rounded px-2 py-1 text-sm text-gray-200 focus:outline-none focus:border-green-500" placeholder="Opsional...">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-800 flex justify-end gap-3">
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">Simpan Nilai</button>
                </div>
            </div>
        </form>
    @elseif(request('class_id') && request('subject_id'))
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-10 text-center text-gray-500">
            Tidak ada data siswa. Pastikan siswa sudah terdaftar di kelas tersebut.
        </div>
    @endif
</x-layouts.teacher>
