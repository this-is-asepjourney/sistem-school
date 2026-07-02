<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Daftar kelas yang diajar oleh guru ini
        $myClasses = ClassRoom::whereHas('subjects', fn ($q) => $q->where('class_subject.user_id', $user->id))
            ->orWhere('homeroom_teacher_id', $user->id)
            ->with('academicYear')
            ->get();

        return view('teacher.attendance.index', compact('myClasses'));
    }

    /**
     * Input absensi untuk kelas & mata pelajaran tertentu
     */
    public function input(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'date' => 'required|date',
        ]);

        $class = ClassRoom::with('students.user')->findOrFail($request->class_id);
        $subject = $request->subject_id ? Subject::find($request->subject_id) : null;
        $date = $request->date;

        // Ambil data absensi yang sudah ada
        $existingAttendance = Attendance::where('class_id', $class->id)
            ->when($subject, fn ($q) => $q->where('subject_id', $subject->id))
            ->whereDate('date', $date)
            ->pluck('status', 'student_id');

        return view('teacher.attendance.input', compact('class', 'subject', 'date', 'existingAttendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:hadir,sakit,izin,alpa',
            'attendance.*.note' => 'nullable|string',
        ]);

        foreach ($request->attendance as $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $data['status'],
                    'note' => $data['note'] ?? null,
                    'noted_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function recap(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $class = ClassRoom::with('students.user')->findOrFail($request->class_id);

        $recap = Student::where('class_id', $class->id)
            ->with(['attendance' => fn ($q) => $q->where('class_id', $class->id)])
            ->get()
            ->map(function ($student) {
                $total = $student->attendance->count();
                $statusCounts = $student->attendance->groupBy('status')->map->count();

                return [
                    'student' => $student,
                    'total' => $total,
                    'hadir' => $statusCounts['hadir'] ?? 0,
                    'sakit' => $statusCounts['sakit'] ?? 0,
                    'izin' => $statusCounts['izin'] ?? 0,
                    'alpa' => $statusCounts['alpa'] ?? 0,
                    'percentage' => $total > 0 ? round((($statusCounts['hadir'] ?? 0) / $total) * 100, 1) : 0,
                ];
            });

        return view('teacher.attendance.recap', compact('class', 'recap'));
    }
}
