<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Grade;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user->student?->load(['class.subjects', 'payments']);
        $activeYear = AcademicYear::active();

        $stats = [
            'avg_grade' => Grade::where('student_id', $student?->id)
                ->where('type', 'rapor')
                ->when($activeYear, fn ($q) => $q->where('academic_year_id', $activeYear->id))
                ->avg('score'),
            'attendance_percent' => $student?->attendancePercentage() ?? 0,
            'pending_assignments' => $student ? Assignment::where('class_id', $student->class_id)
                ->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', $student->id))
                ->where('due_date', '>=', now())
                ->count() : 0,
            'pending_payments' => Payment::where('student_id', $student?->id)
                ->where('status', 'pending')
                ->count(),
        ];

        $upcomingAssignments = $student ? Assignment::where('class_id', $student->class_id)
            ->with('subject')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->limit(5)
            ->get() : collect();

        $announcements = Announcement::whereIn('target', ['all', 'siswa'])
            ->where('is_published', true)
            ->latest()
            ->limit(5)
            ->get();

        $recentGrades = Grade::where('student_id', $student?->id)
            ->with('subject')
            ->latest()
            ->limit(5)
            ->get();

        return view('student.dashboard', compact(
            'student', 'stats', 'upcomingAssignments', 'announcements', 'recentGrades'
        ));
    }
}
