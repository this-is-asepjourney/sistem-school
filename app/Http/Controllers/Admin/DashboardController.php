<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $activeYear = AcademicYear::active();

        $stats = [
            'total_students' => Student::where('status', 'aktif')->count(),
            'total_teachers' => Teacher::where('status', 'aktif')->count(),
            'total_classes' => ClassRoom::when($activeYear, fn ($q) => $q->where('academic_year_id', $activeYear->id))->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        $recentStudents = Student::with('user', 'class')
            ->latest()
            ->limit(5)
            ->get();

        $recentPayments = Payment::with('student.user')
            ->latest()
            ->limit(5)
            ->get();

        // Absensi hari ini
        $todayAttendance = Attendance::whereDate('date', today())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'stats', 'recentStudents', 'recentPayments', 'todayAttendance', 'activeYear'
        ));
    }
}
