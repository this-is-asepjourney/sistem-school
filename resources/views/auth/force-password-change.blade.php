<x-layouts::auth :title="__('Ubah Password')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Wajib Ubah Password')" :description="__('Silakan ubah password Anda sebelum melanjutkan ke dashboard.')" />

        <form method="POST" action="{{ route('password.update.force') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Password Baru -->
            <flux:input
                name="password"
                :label="__('Password Baru')"
                type="password"
                required
                autofocus
                autocomplete="new-password"
                viewable
            />

            <!-- Konfirmasi Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Konfirmasi Password')"
                type="password"
                required
                autocomplete="new-password"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">
                    {{ __('Simpan Password') }}
                </flux:button>
            </div>
        </form>
        
        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-indigo-600 hover:underline">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts::auth>
