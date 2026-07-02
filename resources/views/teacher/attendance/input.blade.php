<x-layouts.teacher :title="'Input Absensi'">
    <div class="max-w-4xl mx-auto">
        {{-- Filter Form --}}
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-5 mb-6">
            <h3 class="text-sm font-semibold text-white mb-4">Pilih Kelas & Tanggal</h3>
            <form method="GET" action="{{ route('teacher.attendance.input') }}" class="flex flex-wrap gap-3">
                <select name="class_id" required class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-green-500">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($myClasses as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}" required
                       class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-green-500">
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">Tampilkan</button>
            </form>
        </div>

        {{-- Absensi Form --}}
        @if(isset($class))
            <form method="POST" action="{{ route('teacher.attendance.store') }}">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                @if(isset($subject)) <input type="hidden" name="subject_id" value="{{ $subject->id }}"> @endif
                <input type="hidden" name="date" value="{{ $date }}">

                <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b border-gray-800">
                        <div>
                            <h3 class="text-sm font-semibold text-white">{{ $class->name }} — {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</h3>
                            <p class="text-xs text-gray-400">{{ $class->students->count() }} siswa</p>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="setAll('hadir')" class="text-xs px-3 py-1.5 bg-green-600/20 hover:bg-green-600/30 text-green-400 rounded-lg transition-colors">Semua Hadir</button>
                        </div>
                    </div>

                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 border-b border-gray-800 bg-gray-800/50">
                                <th class="text-left px-4 py-3">Nama Siswa</th>
                                <th class="text-center px-4 py-3">Hadir</th>
                                <th class="text-center px-4 py-3">Sakit</th>
                                <th class="text-center px-4 py-3">Izin</th>
                                <th class="text-center px-4 py-3">Alpa</th>
                                <th class="text-left px-4 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach($class->students as $student)
                                @php $currentStatus = $existingAttendance[$student->id] ?? 'hadir'; @endphp
                                <input type="hidden" name="attendance[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                <tr class="hover:bg-gray-800/30 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-7 h-7 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xs font-bold">
                                                {{ $student->user?->initials() }}
                                            </div>
                                            <span class="text-white">{{ $student->user?->name }}</span>
                                        </div>
                                    </td>
                                    @foreach(['hadir', 'sakit', 'izin', 'alpa'] as $status)
                                        <td class="px-4 py-3 text-center">
                                            <input type="radio" name="attendance[{{ $loop->parent->index }}][status]" value="{{ $status }}"
                                                   {{ $currentStatus === $status ? 'checked' : '' }}
                                                   class="w-4 h-4 accent-green-500">
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3">
                                        <input type="text" name="attendance[{{ $loop->index }}][note]"
                                               placeholder="Opsional..."
                                               class="w-full bg-gray-800 border border-gray-700 rounded px-2 py-1 text-xs text-gray-300 focus:outline-none focus:border-green-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="p-4 border-t border-gray-800 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                            Simpan Absensi
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <script>
        function setAll(status) {
            document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(r => r.checked = true);
        }
    </script>
</x-layouts.teacher>
