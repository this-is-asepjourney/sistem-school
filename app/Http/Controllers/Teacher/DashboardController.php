<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeYear = AcademicYear::active();

        // Kelas yang diajar guru ini
        $myClasses = ClassRoom::whereHas('subjects', fn ($q) => $q->where('class_subject.user_id', $user->id))
            ->orWhere('homeroom_teacher_id', $user->id)
            ->with('students')
            ->get();

        $stats = [
            'total_classes' => $myClasses->count(),
            'total_students' => $myClasses->sum(fn ($c) => $c->students->count()),
            'pending_submissions' => Assignment::where('created_by', $user->id)
                ->withCount(['submissions as ungr' => fn ($q) => $q->whereNull('graded_at')])
                ->get()
                ->sum('ungr'),
            'today_attendance' => Attendance::where('noted_by', $user->id)
                ->whereDate('date', today())
                ->count(),
        ];

        $recentAssignments = Assignment::where('created_by', $user->id)
            ->with('class', 'subject')
            ->withCount('submissions')
            ->latest()
            ->limit(5)
            ->get();

        $announcements = Announcement::whereIn('target', ['all', 'guru'])
            ->where('is_published', true)
            ->latest()
            ->limit(5)
            ->get();

        return view('teacher.dashboard', compact('stats', 'myClasses', 'recentAssignments', 'announcements'));
    }
}
