<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\Student;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

// ─── Admin Routes ────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Tahun Ajaran
        Route::resource('academic-years', Admin\AcademicYearController::class);
        Route::post('academic-years/{academicYear}/set-active', [Admin\AcademicYearController::class, 'setActive'])
            ->name('academic-years.set-active');

        // Siswa
        Route::resource('students', Admin\StudentController::class);
        Route::post('students/import', [Admin\StudentController::class, 'import'])->name('students.import');

        // Guru
        Route::resource('teachers', Admin\TeacherController::class);

        // Kelas
        Route::resource('classes', Admin\ClassController::class);

        // Mata Pelajaran
        Route::resource('subjects', Admin\SubjectController::class);

        // Absensi (rekap)
        Route::get('attendance', [Admin\AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/export', [Admin\AttendanceController::class, 'export'])->name('attendance.export');

        // Pembayaran SPP
        Route::resource('payments', Admin\PaymentController::class)->except('show', 'edit', 'update');
        Route::post('payments/{payment}/mark-paid', [Admin\PaymentController::class, 'markPaid'])->name('payments.mark-paid');
        Route::post('payments/generate-bulk', [Admin\PaymentController::class, 'generateBulk'])->name('payments.generate-bulk');

        // Pengumuman
        Route::resource('announcements', Admin\AnnouncementController::class)->except('show');
    });

// ─── Teacher Routes ───────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:guru'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [Teacher\DashboardController::class, 'index'])->name('dashboard');

        // Absensi
        Route::get('attendance', [Teacher\AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/input', [Teacher\AttendanceController::class, 'input'])->name('attendance.input');
        Route::post('attendance', [Teacher\AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('attendance/recap', [Teacher\AttendanceController::class, 'recap'])->name('attendance.recap');

        // Nilai
        Route::get('grades', [Teacher\GradeController::class, 'index'])->name('grades.index');
        Route::post('grades', [Teacher\GradeController::class, 'store'])->name('grades.store');
        Route::post('grades/calculate-rapor', [Teacher\GradeController::class, 'calculateRapor'])->name('grades.calculate-rapor');

        // Tugas
        Route::resource('assignments', Teacher\AssignmentController::class)->except('edit', 'update');
        Route::post('submissions/{submission}/grade', [Teacher\AssignmentController::class, 'gradeSubmission'])->name('submissions.grade');
    });

// ─── Student Routes ───────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:siswa'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');

        // Tugas & Submission
        Route::get('assignments', [Student\SubmissionController::class, 'index'])->name('assignments.index');
        Route::get('assignments/{assignment}', [Student\SubmissionController::class, 'show'])->name('assignments.show');
        Route::post('assignments/{assignment}/submit', [Student\SubmissionController::class, 'store'])->name('assignments.submit');

        // Laporan & Nilai
        Route::get('grades', [Student\ReportController::class, 'grades'])->name('grades');
        Route::get('attendance', [Student\ReportController::class, 'attendance'])->name('attendance');
        Route::get('payments', [Student\ReportController::class, 'payments'])->name('payments');
        Route::get('rapor/download', [Student\ReportController::class, 'downloadRapor'])->name('rapor.download');
    });

// ─── Redirect after login based on role ─────────────────────────
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $role = auth()->user()->role;
    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru' => redirect()->route('teacher.dashboard'),
        'siswa' => redirect()->route('student.dashboard'),
        default => redirect()->route('home'),
    };
})->name('dashboard');

require __DIR__.'/settings.php';
