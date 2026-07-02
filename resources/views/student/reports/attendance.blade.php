<x-layouts.student :title="'Absensi Saya'">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Rekap Kehadiran</h2>

        {{-- Summary --}}
        <div class="grid grid-cols-4 gap-3 mb-5">
            @foreach(['hadir' => ['Hadir', 'text-green-400', 'bg-green-900/30'], 'sakit' => ['Sakit', 'text-yellow-400', 'bg-yellow-900/30'], 'izin' => ['Izin', 'text-blue-400', 'bg-blue-900/30'], 'alpa' => ['Alpa', 'text-red-400', 'bg-red-900/30']] as $key => [$label, $color, $bg])
                <div class="bg-gray-900 rounded-xl border border-gray-800 p-4 text-center">
                    <p class="text-xs text-gray-400">{{ $label }}</p>
                    <p class="text-2xl font-bold {{ $color }} mt-1">{{ $statusSummary[$key] ?? 0 }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex gap-3 mb-5">
        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-violet-500">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-violet-500">
        <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg text-sm transition-colors">Filter</button>
    </form>

    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 border-b border-gray-800 bg-gray-800/50">
                    <th class="text-left px-4 py-3">Tanggal</th>
                    <th class="text-left px-4 py-3">Mata Pelajaran</th>
                    <th class="text-center px-4 py-3">Status</th>
                    <th class="text-left px-4 py-3">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($attendances as $att)
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="px-4 py-3 text-gray-200">{{ $att->date->isoFormat('ddd, D MMM Y') }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ $att->subject?->name ?? 'Umum' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $att->status === 'hadir' ? 'bg-green-900/50 text-green-400' : ($att->status === 'sakit' ? 'bg-yellow-900/50 text-yellow-400' : ($att->status === 'izin' ? 'bg-blue-900/50 text-blue-400' : 'bg-red-900/50 text-red-400')) }}">
                                {{ $att->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $att->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada data absensi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $attendances->links() }}</div>
</x-layouts.student>
