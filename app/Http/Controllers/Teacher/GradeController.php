<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeYear = AcademicYear::active();

        $mySubjects = Subject::whereHas('teachers', fn ($q) => $q->where('users.id', $user->id))
            ->get();

        $myClasses = ClassRoom::whereHas('subjects', fn ($q) => $q->where('class_subject.user_id', $user->id))
            ->get();

        $grades = collect();
        if ($request->class_id && $request->subject_id) {
            $grades = Grade::with('student.user')
                ->where('subject_id', $request->subject_id)
                ->whereHas('student', fn ($q) => $q->where('class_id', $request->class_id))
                ->when($activeYear, fn ($q) => $q->where('academic_year_id', $activeYear->id))
                ->when($request->semester, fn ($q) => $q->where('semester', $request->semester))
                ->when($request->type, fn ($q) => $q->where('type', $request->type))
                ->get();
        }

        return view('teacher.grades.index', compact('mySubjects', 'myClasses', 'grades'));
    }

    public function store(Request $request)
    {
        $activeYear = AcademicYear::active();

        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.subject_id' => 'required|exists:subjects,id',
            'grades.*.semester' => 'required|in:1,2',
            'grades.*.type' => 'required|in:uh,uts,uas,tugas,rapor',
            'grades.*.score' => 'nullable|numeric|min:0|max:100',
            'grades.*.note' => 'nullable|string',
        ]);

        foreach ($request->grades as $data) {
            Grade::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'subject_id' => $data['subject_id'],
                    'academic_year_id' => $activeYear?->id,
                    'semester' => $data['semester'],
                    'type' => $data['type'],
                ],
                [
                    'score' => $data['score'] ?? null,
                    'note' => $data['note'] ?? null,
                    'graded_by' => auth()->id(),
                ]
            );
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Hitung dan simpan nilai rapor otomatis
     */
    public function calculateRapor(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required|in:1,2',
        ]);

        $activeYear = AcademicYear::active();
        $students = Student::where('class_id', $request->class_id)->get();

        foreach ($students as $student) {
            $avg = Grade::where('student_id', $student->id)
                ->where('subject_id', $request->subject_id)
                ->where('academic_year_id', $activeYear?->id)
                ->where('semester', $request->semester)
                ->whereIn('type', ['uh', 'uts', 'uas', 'tugas'])
                ->whereNotNull('score')
                ->avg('score');

            if ($avg !== null) {
                Grade::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_id' => $request->subject_id,
                        'academic_year_id' => $activeYear?->id,
                        'semester' => $request->semester,
                        'type' => 'rapor',
                    ],
                    [
                        'score' => round($avg, 2),
                        'graded_by' => auth()->id(),
                    ]
                );
            }
        }

        return back()->with('success', 'Nilai rapor berhasil dihitung.');
    }
}
