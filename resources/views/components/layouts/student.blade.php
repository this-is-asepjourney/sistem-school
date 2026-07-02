<x-layouts.base :title="$title ?? 'Portal Siswa'" roleName="Siswa">
    <x-slot:sidebar>
        <x-nav-item href="{{ route('student.dashboard') }}" icon="home" :active="request()->routeIs('student.dashboard')">Dashboard</x-nav-item>

        <div class="pt-5 pb-2 px-3">
            <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(211,218,217,0.35); letter-spacing:.12em;">Akademik</p>
        </div>
        <x-nav-item href="{{ route('student.assignments.index') }}" icon="book" :active="request()->routeIs('student.assignments.*')">Tugas & Ujian</x-nav-item>
        <x-nav-item href="{{ route('student.grades') }}" icon="chart" :active="request()->routeIs('student.grades')">Nilai & Rapor</x-nav-item>
        <x-nav-item href="{{ route('student.attendance') }}" icon="clipboard" :active="request()->routeIs('student.attendance')">Rekap Kehadiran</x-nav-item>

        <div class="pt-5 pb-2 px-3">
            <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(211,218,217,0.35); letter-spacing:.12em;">Administrasi</p>
        </div>
        <x-nav-item href="{{ route('student.payments') }}" icon="credit-card" :active="request()->routeIs('student.payments')">Pembayaran SPP</x-nav-item>
    </x-slot:sidebar>

    {{ $slot }}
</x-layouts.base>
