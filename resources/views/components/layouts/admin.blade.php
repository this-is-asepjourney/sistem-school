<x-layouts.base :title="$title ?? 'Dashboard Admin'" roleName="Administrator">
    <x-slot:sidebar>
        <x-nav-item href="{{ route('admin.dashboard') }}" icon="home" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-item>

        <div class="pt-5 pb-2 px-3">
            <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(211,218,217,0.35); letter-spacing:.12em;">Akademik</p>
        </div>
        <x-nav-item href="{{ route('admin.academic-years.index') }}" icon="calendar" :active="request()->routeIs('admin.academic-years.*')">Tahun Ajaran</x-nav-item>
        <x-nav-item href="{{ route('admin.classes.index') }}" icon="building" :active="request()->routeIs('admin.classes.*')">Kelas</x-nav-item>
        <x-nav-item href="{{ route('admin.subjects.index') }}" icon="book" :active="request()->routeIs('admin.subjects.*')">Mata Pelajaran</x-nav-item>
        <x-nav-item href="{{ route('admin.attendance.index') }}" icon="clipboard" :active="request()->routeIs('admin.attendance.*')">Rekap Absensi</x-nav-item>

        <div class="pt-5 pb-2 px-3">
            <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(211,218,217,0.35); letter-spacing:.12em;">Pengguna</p>
        </div>
        <x-nav-item href="{{ route('admin.students.index') }}" icon="users" :active="request()->routeIs('admin.students.*')">Siswa</x-nav-item>
        <x-nav-item href="{{ route('admin.teachers.index') }}" icon="user" :active="request()->routeIs('admin.teachers.*')">Guru & Staf</x-nav-item>

        <div class="pt-5 pb-2 px-3">
            <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(211,218,217,0.35); letter-spacing:.12em;">Keuangan</p>
        </div>
        <x-nav-item href="{{ route('admin.payments.index') }}" icon="credit-card" :active="request()->routeIs('admin.payments.*')">Pembayaran SPP</x-nav-item>

        <div class="pt-5 pb-2 px-3">
            <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(211,218,217,0.35); letter-spacing:.12em;">Komunikasi</p>
        </div>
        <x-nav-item href="{{ route('admin.announcements.index') }}" icon="bell" :active="request()->routeIs('admin.announcements.*')">Pengumuman</x-nav-item>
    </x-slot:sidebar>

    {{ $slot }}
</x-layouts.base>
