<x-layouts.admin :title="'Tambah Siswa Baru'">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.students.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold mb-4 transition-opacity hover:opacity-70"
               style="color:var(--c-muted);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Siswa
            </a>
            <h2 class="text-2xl font-bold" style="color:var(--c-dark);">Pendaftaran Siswa Baru</h2>
            <p class="text-sm font-medium mt-1" style="color:var(--c-muted);">Lengkapi semua informasi di bawah untuk membuat akun dan profil siswa.</p>
        </div>

        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-5">
            @csrf

            {{-- 1. Informasi Akun --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #E8EAED;">
                <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid #F0F1F3; background:#FAFAFA;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                         style="background:var(--c-dark);">1</div>
                    <h3 class="text-sm font-bold" style="color:var(--c-dark);">Informasi Akun Login</h3>
                    <span class="text-xs font-medium ml-auto" style="color:var(--c-muted);">Digunakan untuk masuk ke sistem</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">Nama Lengkap <span style="color:#E53E3E;">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium transition-colors @error('name') border-red-400 @enderror"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                            @error('name')<p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">Email <span style="color:#E53E3E;">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@sekolah.sch.id"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium transition-colors @error('email') border-red-400 @enderror"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                            @error('email')<p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">Password <span style="color:#E53E3E;">*</span></label>
                            <input type="password" name="password" required placeholder="Minimal 6 karakter"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium transition-colors @error('password') border-red-400 @enderror"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                            @error('password')<p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Data Akademik --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #E8EAED;">
                <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid #F0F1F3; background:#FAFAFA;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                         style="background:var(--c-medium);">2</div>
                    <h3 class="text-sm font-bold" style="color:var(--c-dark);">Data Akademik</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">NIS</label>
                            <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Nomor Induk Siswa"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium @error('nis') border-red-400 @enderror"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                            @error('nis')<p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">NISN</label>
                            <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa Nasional"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">Kelas</label>
                            <select name="class_id" style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                    class="w-full rounded-xl px-4 py-2.5 text-sm font-medium"
                                    onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Data Pribadi --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #E8EAED;">
                <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid #F0F1F3; background:#FAFAFA;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                         style="background:var(--c-muted);">3</div>
                    <h3 class="text-sm font-bold" style="color:var(--c-dark);">Data Pribadi</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">Jenis Kelamin</label>
                            <select name="gender" style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                    class="w-full rounded-xl px-4 py-2.5 text-sm font-medium"
                                    onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">No. Handphone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">Tempat Lahir</label>
                            <input type="text" name="birth_place" value="{{ old('birth_place') }}" placeholder="Kota kelahiran"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">Alamat Lengkap</label>
                            <textarea name="address" rows="3" placeholder="Jalan, nomor rumah, kelurahan, kecamatan..."
                                      style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none; resize:none;"
                                      class="w-full rounded-xl px-4 py-2.5 text-sm font-medium"
                                      onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Data Orang Tua --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #E8EAED;">
                <div class="px-6 py-4 flex items-center gap-3" style="border-bottom:1px solid #F0F1F3; background:#FAFAFA;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                         style="background:#AAB0AF;">4</div>
                    <h3 class="text-sm font-bold" style="color:var(--c-dark);">Data Orang Tua / Wali</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">Nama Orang Tua / Wali</label>
                            <input type="text" name="parent_name" value="{{ old('parent_name') }}" placeholder="Nama lengkap orang tua"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--c-muted); letter-spacing:.08em;">No. HP Orang Tua</label>
                            <input type="text" name="parent_phone" value="{{ old('parent_phone') }}" placeholder="08xx-xxxx-xxxx"
                                   style="background:#F8F9FA; border:1.5px solid #E8EAED; color:var(--c-dark); outline:none;"
                                   class="w-full rounded-xl px-4 py-2.5 text-sm font-medium"
                                   onfocus="this.style.borderColor='var(--c-muted)'" onblur="this.style.borderColor='#E8EAED'">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-4 pt-2 pb-10">
                <a href="{{ route('admin.students.index') }}"
                   class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all hover:opacity-80"
                   style="background:white; border:1.5px solid #E8EAED; color:var(--c-medium);">
                    Batal
                </a>
                <button type="submit"
                        class="px-7 py-2.5 rounded-xl text-sm font-bold text-white shadow-md transition-all hover:opacity-90 hover:shadow-lg"
                        style="background:var(--c-dark);">
                    Daftarkan Siswa
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
