<x-layouts.admin :title="'Pembayaran SPP'">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--c-dark);">Manajemen SPP</h2>
            <p class="text-sm font-medium mt-1" style="color:var(--c-muted);">Total {{ $payments->total() }} tagihan pembayaran.</p>
        </div>
        <a href="{{ route('admin.payments.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all hover:opacity-90"
           style="background:var(--c-dark);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Tagihan
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #E8EAED;">
            <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.1em;">Total Lunas</p>
            <p class="text-2xl font-bold" style="color:#065F46;">Rp {{ number_format($summary['total_paid'], 0, ',', '.') }}</p>
            <div class="w-full h-1 rounded-full mt-3" style="background:#D1FAE5;"></div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #E8EAED;">
            <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.1em;">Belum Bayar</p>
            <p class="text-2xl font-bold" style="color:#92400E;">Rp {{ number_format($summary['total_pending'], 0, ',', '.') }}</p>
            <div class="w-full h-1 rounded-full mt-3" style="background:#FEF3C7;"></div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #E8EAED;">
            <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.1em;">Terlambat</p>
            <p class="text-2xl font-bold" style="color:#991B1B;">Rp {{ number_format($summary['total_overdue'], 0, ',', '.') }}</p>
            <div class="w-full h-1 rounded-full mt-3" style="background:#FEE2E2;"></div>
        </div>
    </div>

    {{-- Generate Bulk SPP --}}
    <details class="mb-5 bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #E8EAED;">
        <summary class="px-5 py-4 text-sm font-bold cursor-pointer flex items-center gap-2 select-none"
                  style="color:var(--c-dark);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Generate Tagihan SPP Massal
        </summary>
        <form method="POST" action="{{ route('admin.payments.generate-bulk') }}" class="px-5 py-4 flex flex-col sm:flex-row gap-3" style="border-top:1px solid #F0F1F3; background:#FAFAFA;">
            @csrf
            <input type="month" name="period" required
                   class="rounded-xl px-4 py-2.5 text-sm font-medium"
                   style="background:white; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
            <input type="number" name="amount" required placeholder="Nominal SPP (Rp)"
                   class="flex-1 rounded-xl px-4 py-2.5 text-sm font-medium"
                   style="background:white; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
                    style="background:var(--c-dark);">Generate Massal</button>
        </form>
    </details>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm mb-5" style="border:1px solid #E8EAED;">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..."
                   class="rounded-xl px-4 py-2.5 text-sm font-medium"
                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none; min-width:180px;"
                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
            <input type="month" name="period" value="{{ request('period') }}"
                   class="rounded-xl px-4 py-2.5 text-sm font-medium"
                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
            <select name="status" class="rounded-xl px-4 py-2.5 text-sm font-medium"
                    style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                    onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Terlambat</option>
            </select>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
                    style="background:var(--c-medium);">Cari</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #E8EAED;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:2px solid #F0F1F3; background:#FAFAFA;">
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Siswa</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Jenis</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Periode</th>
                        <th class="text-right px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Jumlah</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Status</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--c-muted); letter-spacing:.08em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr style="border-bottom:1px solid #F4F5F7;"
                            onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                            <td class="px-6 py-4 font-bold" style="color:var(--c-dark);">{{ $payment->student?->user?->name }}</td>
                            <td class="px-6 py-4 font-medium" style="color:var(--c-medium);">{{ $payment->type }}</td>
                            <td class="px-6 py-4 font-medium" style="color:#AAB0AF;">{{ $payment->period ?? '—' }}</td>
                            <td class="px-6 py-4 text-right font-bold" style="color:var(--c-dark);">{{ $payment->formatted_amount }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold"
                                      style="{{ $payment->status === 'paid'
                                        ? 'background:#D1FAE5; color:#065F46;'
                                        : ($payment->status === 'overdue'
                                            ? 'background:#FEE2E2; color:#991B1B;'
                                            : 'background:#FEF3C7; color:#92400E;') }}">
                                    {{ $payment->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($payment->status !== 'paid')
                                        <form method="POST" action="{{ route('admin.payments.mark-paid', $payment) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg font-bold transition-all hover:opacity-80"
                                                    style="background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0;">✓ Lunas</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" onsubmit="return confirm('Hapus tagihan ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="transition-opacity hover:opacity-60" style="color:#E53E3E;" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm font-medium" style="color:#AAB0AF;">Belum ada data tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $payments->links() }}</div>
</x-layouts.admin>
