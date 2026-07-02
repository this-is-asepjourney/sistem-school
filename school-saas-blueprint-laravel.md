# Blueprint — Sistem Informasi Sekolah SaaS (K-12)
> Revisi: Laravel 11 + MySQL · Multi-tenant · Admin, Guru, Siswa

---

## 1. Gambaran Umum

Platform web manajemen sekolah berbasis SaaS dengan Laravel sebagai backend utama. Setiap sekolah mendapat subdomain dan database schema tersendiri (schema-per-tenant). Sistem ini menangani seluruh kebutuhan akademik dan administrasi sekolah K-12.

**Model bisnis:** Subscription per sekolah (Trial / Basic / Pro / Enterprise)  
**Target pengguna:** Admin/TU, Guru, Siswa  
**Skala awal:** 1–500 sekolah

---

## 2. Tech Stack

### Core Backend
| Teknologi | Kegunaan |
|-----------|----------|
| Laravel 11 | Framework PHP utama, MVC, Eloquent ORM |
| PHP 8.3 | Typed properties, enums, readonly classes |
| Laravel Sanctum | API token auth + SPA session |
| Spatie Permission | RBAC roles & permissions |
| Laravel Queue (Redis) | Job async: email, PDF, notifikasi |

### Package Laravel
| Package | Kegunaan |
|---------|----------|
| Filament v3 | Admin panel siap pakai + CRUD cepat |
| stancl/tenancy | Multi-tenant schema-per-tenant |
| Spatie Media Library | Upload & manajemen file/foto |
| Laravel Excel (Maatwebsite) | Import/export Excel & CSV |
| DomPDF / Browsershot | Generate rapor & dokumen PDF |
| Laravel Telescope | Debugging & monitoring (dev) |

### Frontend
| Teknologi | Kegunaan |
|-----------|----------|
| Blade + Livewire 3 | Komponen reaktif tanpa SPA penuh |
| Alpine.js | Interaksi JS ringan (dropdown, modal) |
| Tailwind CSS | Utility-first styling |
| Vite | Asset bundling (bawaan Laravel 11) |

### Database & Storage
| Teknologi | Kegunaan |
|-----------|----------|
| MySQL 8 | Database utama, schema-per-tenant |
| Redis | Cache, session, queue driver |
| Cloudflare R2 / AWS S3 | File storage via Laravel Storage |

### Infrastruktur
| Teknologi | Kegunaan |
|-----------|----------|
| Docker Compose | Container: php-fpm, nginx, mysql, redis |
| Nginx | Wildcard subdomain + SSL termination |
| GCP Compute Engine (asia-southeast2) | Cloud hosting |
| GitHub Actions | CI/CD: test → build → deploy |
| Let's Encrypt Wildcard | SSL *.edu-platform.id |

---

## 3. Arsitektur Sistem

### Multi-tenant Strategy: Schema-per-Tenant (stancl/tenancy)

Dua koneksi database:
- `central` → data platform: `tenants`, `plans`, `billing`, super-admin
- `tenant` → data sekolah: `users`, `students`, `grades`, dst (per sekolah)

**Flow request:**
```
User → https://smpn1jakarta.edu-platform.id
  → Nginx (wildcard *.edu-platform.id)
  → Laravel TenantMiddleware
  → Resolve tenant dari subdomain (lookup tabel tenants)
  → Switch DB connection ke schema tenant
  → Spatie Permission resolve role user
  → Controller / Livewire Component / Filament Resource
```

### Struktur Folder

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # StudentController, TeacherController, ReportController
│   │   ├── Teacher/        # AttendanceController, GradeController, AssignmentController
│   │   └── Student/        # DashboardController, SubmissionController
│   ├── Middleware/
│   │   ├── TenantMiddleware.php
│   │   └── RoleMiddleware.php
│   └── Requests/           # Form Request per fitur (validasi)
├── Models/
│   ├── Central/            # Tenant.php, Plan.php
│   └── Tenant/             # User, Student, ClassRoom, Subject, Grade...
├── Services/               # GradeCalculator, ReportGenerator, AttendanceService
├── Actions/                # CreateStudent, GenerateRapor, ProvisionTenant
├── Jobs/                   # SendEmailJob, GeneratePdfJob, SendWaNotifJob
└── Filament/
    ├── Resources/          # StudentResource, TeacherResource, ClassResource, dst
    └── Pages/              # Dashboard, Reports, Settings

database/
├── migrations/
│   ├── central/            # tenants, plans, billing tables
│   └── tenant/             # users, students, grades, attendance, dst
└── seeders/

resources/
├── views/
│   ├── admin/              # Blade admin (non-Filament)
│   ├── teacher/
│   ├── student/
│   └── components/         # Livewire + Blade reusable components
└── js/ & css/              # Vite-managed assets

routes/
├── web.php                 # Tenant routes (semua fitur sekolah)
├── central.php             # Super-admin, landing page, registrasi sekolah
└── api.php                 # REST API endpoint (opsional)
```

---

## 4. Fitur per Role

### 4.1 Admin / Tata Usaha

**Data Master (via Filament Resources):**
- CRUD data siswa + import massal via CSV (Laravel Excel)
- CRUD guru & staf, assign mata pelajaran
- Setup kelas, jurusan, program studi
- Manajemen mata pelajaran per jenjang
- Pengaturan tahun ajaran & semester

**Akademik & Operasional:**
- Builder jadwal pelajaran visual (Livewire)
- Rekap & ekspor absensi keseluruhan
- Monitoring nilai seluruh kelas
- Generate & cetak rapor PDF massal (DomPDF)
- Kalender akademik & hari libur

**Keuangan & Administrasi:**
- Tagihan & pencatatan pembayaran SPP
- Laporan keuangan bulanan/tahunan
- Export data Excel via Laravel Excel
- Manajemen pengumuman ke semua pengguna
- Manajemen dokumen & surat resmi

### 4.2 Guru

**Pembelajaran:**
- Buat & kelola tugas + deadline
- Upload materi (PDF, video) via Spatie Media Library → R2
- Input nilai UH, UTS, UAS, tugas harian
- Koreksi & beri feedback submission siswa
- Kalkulator nilai akhir & bobot rapor otomatis

**Kelas & Absensi:**
- Absensi digital per pertemuan (Livewire real-time)
- Daftar siswa per kelas yang diajar
- Catatan perkembangan & perilaku siswa
- Rekap kehadiran per mata pelajaran

**Komunikasi:**
- Pengumuman ke kelas tertentu
- Notifikasi deadline tugas ke siswa (email + in-app)
- Jadwal mengajar & ujian

### 4.3 Siswa

**Akademik:**
- Lihat jadwal pelajaran & ujian
- Download materi dari guru
- Submit tugas (upload file)
- Lihat nilai & feedback guru
- Rapor digital per semester (unduh PDF)

**Informasi & Administrasi:**
- Riwayat absensi personal
- Notifikasi tugas mendekati deadline
- Kalender akademik & pengumuman
- Lihat tagihan SPP & status bayar
- Download surat keterangan aktif
- Ajukan izin tidak hadir (online)

---

## 5. Database Schema (Core Tables)

### Central Database

```php
// Migration: create_tenants_table.php
Schema::create('tenants', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('subdomain')->unique();
    $table->string('database')->unique();      // nama schema MySQL
    $table->enum('plan', ['trial','basic','pro','enterprise'])->default('trial');
    $table->enum('status', ['active','suspended','expired'])->default('active');
    $table->integer('max_students')->default(200);
    $table->dateTime('trial_ends_at')->nullable();
    $table->timestamps();
});
```

### Tenant Database

```php
// users
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['admin','guru','siswa']);
    $table->string('avatar')->nullable();
    $table->boolean('is_active')->default(true);
    $table->dateTime('last_login_at')->nullable();
    $table->rememberToken();
    $table->timestamps();
});

// academic_years
Schema::create('academic_years', function (Blueprint $table) {
    $table->id();
    $table->string('name');                    // "2024/2025"
    $table->date('start_date');
    $table->date('end_date');
    $table->boolean('is_active')->default(false);
    $table->timestamps();
});

// classes (kelas)
Schema::create('classes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('academic_year_id')->constrained();
    $table->string('name');                    // "VII A"
    $table->tinyInteger('grade_level');        // 1-12
    $table->foreignId('homeroom_teacher_id')->nullable()->constrained('users');
    $table->timestamps();
});

// subjects (mata pelajaran)
Schema::create('subjects', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->nullable();
    $table->tinyInteger('grade_level');
    $table->foreignId('teacher_id')->nullable()->constrained('users');
    $table->timestamps();
});

// students
Schema::create('students', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->unique()->constrained();
    $table->foreignId('class_id')->nullable()->constrained('classes');
    $table->string('nis', 20)->unique()->nullable();
    $table->string('nisn', 20)->nullable();
    $table->date('birth_date')->nullable();
    $table->text('address')->nullable();
    $table->string('phone', 20)->nullable();
    $table->timestamps();
});

// attendance
Schema::create('attendance', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained();
    $table->foreignId('class_id')->constrained('classes');
    $table->foreignId('subject_id')->nullable()->constrained();
    $table->date('date');
    $table->enum('status', ['hadir','sakit','izin','alpa']);
    $table->foreignId('noted_by')->nullable()->constrained('users');
    $table->text('note')->nullable();
    $table->timestamps();
});

// grades (nilai)
Schema::create('grades', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained();
    $table->foreignId('subject_id')->constrained();
    $table->foreignId('academic_year_id')->constrained();
    $table->tinyInteger('semester');           // 1 atau 2
    $table->enum('type', ['uh','uts','uas','tugas','rapor']);
    $table->decimal('score', 5, 2)->nullable();
    $table->decimal('max_score', 5, 2)->default(100);
    $table->text('note')->nullable();
    $table->timestamps();
});

// assignments (tugas)
Schema::create('assignments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('subject_id')->constrained();
    $table->foreignId('class_id')->constrained('classes');
    $table->string('title');
    $table->text('description')->nullable();
    $table->dateTime('due_date')->nullable();
    $table->decimal('max_score', 5, 2)->default(100);
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
});

// submissions (pengumpulan tugas)
Schema::create('submissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('assignment_id')->constrained();
    $table->foreignId('student_id')->constrained();
    $table->text('note')->nullable();
    $table->decimal('score', 5, 2)->nullable();
    $table->text('feedback')->nullable();
    $table->dateTime('submitted_at')->nullable();
    $table->dateTime('graded_at')->nullable();
    $table->timestamps();
    $table->unique(['assignment_id', 'student_id']);
});

// payments (SPP)
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained();
    $table->string('type');                    // SPP, kegiatan, dll
    $table->decimal('amount', 12, 2);
    $table->string('period', 7)->nullable();   // "2024-08"
    $table->enum('status', ['pending','paid','overdue'])->default('pending');
    $table->string('payment_proof')->nullable();
    $table->dateTime('paid_at')->nullable();
    $table->timestamps();
});
```

---

## 6. Konfigurasi Multi-tenant (stancl/tenancy)

```php
// config/tenancy.php
'tenant_model' => \App\Models\Central\Tenant::class,
'id_generator' => \Stancl\Tenancy\UUIDGenerator::class,

'bootstrappers' => [
    \Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
    \Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
    \Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
],

'database' => [
    'based_on_connection' => 'tenant',
    'prefix' => 'school_',
    'suffix' => '',
],
```

```php
// routes/central.php — landing & registrasi
Route::group([], function () {
    Route::get('/', [LandingController::class, 'index']);
    Route::post('/register-school', [TenantRegistrationController::class, 'store']);
});

// routes/web.php — tenant routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('students', Admin\StudentController::class);
    Route::resource('teachers', Admin\TeacherController::class);
    Route::resource('classes', Admin\ClassController::class);
    // ...
});

Route::middleware(['auth', 'role:guru'])->prefix('teacher')->group(function () {
    Route::resource('attendance', Teacher\AttendanceController::class);
    Route::resource('grades', Teacher\GradeController::class);
    // ...
});
```

---

## 7. Roadmap Pengembangan (20 Minggu)

### Phase 1 — Foundation & Auth (Minggu 1–4)
- [ ] Setup Laravel 11 + PHP 8.3 + MySQL 8 + Redis
- [ ] Install & konfigurasi stancl/tenancy (schema-per-tenant)
- [ ] Docker Compose: php-fpm + nginx + mysql + redis
- [ ] Nginx wildcard subdomain + Let's Encrypt wildcard SSL
- [ ] Auth: Laravel Sanctum + Spatie Permission RBAC
- [ ] Filament v3 setup + dashboard shell per role
- [ ] Central: landing page + form registrasi + tenant provisioning

### Phase 2 — Data Master & Akademik Dasar (Minggu 5–9)
- [ ] Filament Resource: CRUD siswa + import CSV (Laravel Excel)
- [ ] Filament Resource: CRUD guru, staf, assign mapel
- [ ] Setup kelas, jurusan, mata pelajaran
- [ ] Jadwal pelajaran (Livewire builder visual)
- [ ] Manajemen tahun ajaran & semester
- [ ] Modul absensi Livewire (guru input, rekap admin, export)

### Phase 3 — Fitur Akademik Lanjutan (Minggu 10–15)
- [ ] Input nilai (UH, UTS, UAS, tugas) via Livewire
- [ ] Sistem tugas: buat → submit siswa → koreksi guru
- [ ] Upload file: Spatie Media Library + Cloudflare R2
- [ ] Generate rapor PDF otomatis (DomPDF / Browsershot)
- [ ] Pengumuman & notifikasi email (Laravel Mail + Mailtrap)
- [ ] Portal siswa lengkap (jadwal, nilai, tugas, absensi)
- [ ] Cetak kartu ujian & sertifikat

### Phase 4 — Keuangan, SaaS Billing & Launch (Minggu 16–20)
- [ ] Modul SPP & laporan keuangan
- [ ] Billing SaaS: paket, limit tenant, expired handling
- [ ] Integrasi Midtrans / Xendit (SPP + langganan)
- [ ] Super-admin dashboard analytics (central DB)
- [ ] Onboarding wizard sekolah baru
- [ ] Rate limiting, security audit, N+1 query fix
- [ ] Soft launch + feedback loop

---

## 8. SaaS Subscription Tiers

| Fitur | Trial | Basic | Pro | Enterprise |
|-------|-------|-------|-----|------------|
| Maks siswa | 50 | 300 | 1.000 | Unlimited |
| Storage | 1 GB | 10 GB | 30 GB | 100 GB |
| Admin accounts | 1 | 3 | 10 | Unlimited |
| Generate rapor PDF | ✓ | ✓ | ✓ | ✓ |
| Modul keuangan | — | ✓ | ✓ | ✓ |
| Import CSV massal | — | ✓ | ✓ | ✓ |
| API access | — | — | ✓ | ✓ |
| Custom domain | — | — | — | ✓ |
| Priority support | — | — | ✓ | ✓ |
| Durasi | 14 hari | — | — | — |
| Harga/bulan | Gratis | Rp 299.000 | Rp 699.000 | Custom |

---

## 9. Keamanan

- Password di-hash dengan bcrypt (Laravel default, cost 12)
- Spatie Permission → RBAC di setiap route & Filament resource
- `TenantMiddleware` wajib ada di semua tenant routes
- Rate limiting per IP via `throttle` middleware (Redis)
- Form Request validation di semua endpoint
- File upload: validasi MIME type + size limit (Spatie Media)
- Audit log via Laravel Activity Log (Spatie) untuk aksi kritis
- CSRF protection bawaan Laravel di semua form
- Query injection aman via Eloquent ORM

---

## 10. Integrasi Pihak Ketiga (Opsional/Masa Depan)

- **Midtrans / Xendit** → pembayaran SPP & billing SaaS
- **WhatsApp API (Fonnte / Wablas)** → notifikasi absensi ke orang tua
- **Google OAuth** → SSO login dengan akun Google sekolah
- **Dapodik API** → sinkronisasi data siswa dengan Kemendikbud
- **Twilio / Vonage** → SMS OTP untuk reset password

---

*Blueprint ini adalah panduan awal. Fitur dan prioritas dapat disesuaikan selama pengembangan.*
