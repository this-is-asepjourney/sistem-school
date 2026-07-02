<x-layouts.student :title="'Pembayaran SPP'">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Riwayat Pembayaran SPP</h2>
        <div class="grid grid-cols-2 gap-4 mb-5">
            <div class="bg-green-900/30 border border-green-800 rounded-xl p-4">
                <p class="text-xs text-green-400">Total Lunas</p>
                <p class="text-xl font-bold text-white mt-1">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
            </div>
            <div class="bg-yellow-900/30 border border-yellow-800 rounded-xl p-4">
                <p class="text-xs text-yellow-400">Total Belum Bayar</p>
                <p class="text-xl font-bold text-white mt-1">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 border-b border-gray-800 bg-gray-800/50">
                    <th class="text-left px-4 py-3">Jenis</th>
                    <th class="text-left px-4 py-3">Periode</th>
                    <th class="text-right px-4 py-3">Jumlah</th>
                    <th class="text-center px-4 py-3">Status</th>
                    <th class="text-left px-4 py-3">Dibayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="px-4 py-3 text-white">{{ $payment->type }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ $payment->period ?? '-' }}</td>
                        <td class="px-4 py-3 text-right text-gray-200">{{ $payment->formatted_amount }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $payment->status === 'paid' ? 'bg-green-900/50 text-green-400' : ($payment->status === 'overdue' ? 'bg-red-900/50 text-red-400' : 'bg-yellow-900/50 text-yellow-400') }}">
                                {{ $payment->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $payment->paid_at?->format('d M Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada data pembayaran</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $payments->links() }}</div>
</x-layouts.student>
