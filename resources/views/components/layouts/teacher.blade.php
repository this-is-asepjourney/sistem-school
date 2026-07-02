<x-layouts.base :title="$title ?? 'Portal Guru'" roleName="Guru">
    <x-slot:sidebar>
        <x-nav-item href="{{ route('teacher.dashboard') }}" icon="home" :active="request()->routeIs('teacher.dashboard')">Dashboard</x-nav-item>

        <div class="pt-5 pb-2 px-3">
            <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(211,218,217,0.35); letter-spacing:.12em;">Akademik</p>
        </div>
        <x-nav-item href="{{ route('teacher.attendance.index') }}" icon="clipboard" :active="request()->routeIs('teacher.attendance.*')">Absensi</x-nav-item>
        <x-nav-item href="{{ route('teacher.grades.index') }}" icon="chart" :active="request()->routeIs('teacher.grades.*')">Nilai & Rapor</x-nav-item>
        <x-nav-item href="{{ route('teacher.assignments.index') }}" icon="book" :active="request()->routeIs('teacher.assignments.*')">Tugas & Ujian</x-nav-item>
    </x-slot:sidebar>

    {{ $slot }}
</x-layouts.base>
