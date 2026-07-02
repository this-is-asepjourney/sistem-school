<x-layouts.teacher :title="'Rekap Absensi'">
    <div class="mb-6">
        <a href="{{ route('teacher.attendance.index') }}" class="flex items-center gap-2 text-sm text-gray-400 hover:text-gray-200 mb-4 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h2 class="text-lg font-semibold text-white">Rekap Absensi — {{ $class->name }}</h2>
    </div>
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 border-b border-gray-800 bg-gray-800/50">
                    <th class="text-left px-4 py-3">Nama Siswa</th>
                    <th class="text-center px-4 py-3 text-green-400">Hadir</th>
                    <th class="text-center px-4 py-3 text-yellow-400">Sakit</th>
                    <th class="text-center px-4 py-3 text-blue-400">Izin</th>
                    <th class="text-center px-4 py-3 text-red-400">Alpa</th>
                    <th class="text-center px-4 py-3">Total</th>
                    <th class="text-center px-4 py-3">%</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @foreach($recap as $row)
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="px-4 py-3 text-white">{{ $row['student']->user?->name }}</td>
                        <td class="px-4 py-3 text-center text-green-400 font-medium">{{ $row['hadir'] }}</td>
                        <td class="px-4 py-3 text-center text-yellow-400">{{ $row['sakit'] }}</td>
                        <td class="px-4 py-3 text-center text-blue-400">{{ $row['izin'] }}</td>
                        <td class="px-4 py-3 text-center text-red-400">{{ $row['alpa'] }}</td>
                        <td class="px-4 py-3 text-center text-gray-400">{{ $row['total'] }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-medium {{ $row['percentage'] >= 75 ? 'text-green-400' : 'text-red-400' }}">{{ $row['percentage'] }}%</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.teacher>
