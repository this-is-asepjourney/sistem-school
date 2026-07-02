@props(['title' => 'Dashboard', 'roleName' => 'Portal'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ config('app.name') }}</title>
    <meta name="description" content="Sistem Informasi Sekolah — Portal {{ $roleName }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Brand palette tokens */
        :root {
            --c-dark:   #37353E;
            --c-medium: #44444E;
            --c-muted:  #715A5A;
            --c-light:  #D3DAD9;
            --c-surface:#F8F9FA;
        }
    </style>
</head>
<body style="font-family:'Outfit',sans-serif; background:#F4F5F7;" class="antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ═══════════════════════════════════════════
         SIDEBAR — Dark brand background
    ═══════════════════════════════════════════ --}}
    <aside class="w-72 flex flex-col shrink-0 z-20 overflow-hidden" style="background:var(--c-dark);">

        {{-- Logo / School Identity --}}
        <div class="flex items-center gap-4 px-6 py-5" style="border-bottom: 1px solid rgba(211,218,217,0.12);">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-lg"
                 style="background:var(--c-muted);">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="font-bold text-white text-sm leading-tight truncate" style="letter-spacing:.01em;">
                    {{ config('app.name', 'Sistem Sekolah') }}
                </p>
                <p class="text-xs font-semibold uppercase tracking-widest mt-0.5" style="color:var(--c-light);opacity:.7;">
                    {{ $roleName }} Portal
                </p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-5 px-3 space-y-0.5">
            {{ $sidebar ?? '' }}
        </nav>

        {{-- User Panel --}}
        <div class="p-4 mt-auto" style="border-top: 1px solid rgba(211,218,217,0.12); background:rgba(0,0,0,0.15);">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-xs font-bold shrink-0 shadow"
                     style="background:var(--c-muted);">
                    {{ auth()->user()->initials() ?? 'U' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs truncate mt-0.5" style="color:var(--c-light);opacity:.6;">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            title="Keluar"
                            class="p-2 rounded-lg transition-all duration-150"
                            style="color:rgba(211,218,217,0.5);"
                            onmouseover="this.style.background='rgba(113,90,90,0.25)';this.style.color='#D3DAD9'"
                            onmouseout="this.style.background='transparent';this.style.color='rgba(211,218,217,0.5)'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT AREA
    ═══════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden" style="background:#F4F5F7;">

        {{-- Top Header --}}
        <header class="h-16 bg-white flex items-center justify-between px-8 shrink-0"
                style="border-bottom:1px solid #E8EAED; box-shadow:0 1px 3px rgba(55,53,62,0.06);">
            <div>
                <h1 class="text-lg font-bold" style="color:var(--c-dark);">{{ $title }}</h1>
                @isset($breadcrumb)
                    <p class="text-xs font-medium mt-0.5" style="color:var(--c-muted);">{{ $breadcrumb }}</p>
                @endisset
            </div>
            <div class="flex items-center gap-3">
                {{-- Date badge --}}
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold"
                     style="background:#F4F5F7; color:var(--c-medium); border:1px solid #E8EAED;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-muted);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </div>

                {{-- Role Badge --}}
                <div class="px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-wider"
                     style="background:var(--c-dark); color:var(--c-light);">
                    {{ $roleName }}
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-8 pt-5 shrink-0">
            @if(session('success'))
                <div class="mb-4 flex items-center gap-3 p-4 rounded-2xl text-sm font-medium shadow-sm"
                     style="background:#ECFDF5; border:1px solid #A7F3D0; color:#065F46;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:#D1FAE5;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 flex items-center gap-3 p-4 rounded-2xl text-sm font-medium shadow-sm"
                     style="background:#FEF2F2; border:1px solid #FECACA; color:#991B1B;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:#FEE2E2;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-8 pb-10 pt-2">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
