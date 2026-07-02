<x-layouts.teacher :title="'Daftar Absensi'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-white">Absensi</h2>
        <a href="{{ route('teacher.attendance.input') }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
            + Input Absensi
        </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($myClasses as $class)
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
                <h3 class="font-semibold text-white mb-2">{{ $class->name }}</h3>
                <p class="text-sm text-gray-400 mb-4">Tahun: {{ $class->academicYear?->name }}</p>
                <div class="flex gap-2">
                    <a href="{{ route('teacher.attendance.input', ['class_id' => $class->id, 'date' => today()->format('Y-m-d')]) }}"
                       class="flex-1 text-center text-xs px-3 py-2 bg-green-600/20 hover:bg-green-600/30 text-green-400 rounded-lg transition-colors">
                        Input Hari Ini
                    </a>
                    <a href="{{ route('teacher.attendance.recap', ['class_id' => $class->id]) }}"
                       class="flex-1 text-center text-xs px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg transition-colors">
                        Rekap
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.teacher>
