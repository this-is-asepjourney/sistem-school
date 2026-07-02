<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $assignments = Assignment::where('class_id', $student->class_id)
            ->with(['subject', 'submissions' => fn ($q) => $q->where('student_id', $student->id)])
            ->orderByDesc('due_date')
            ->paginate(15);

        return view('student.submissions.index', compact('assignments', 'student'));
    }

    public function show(Assignment $assignment)
    {
        $student = auth()->user()->student;
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $student?->id)
            ->first();

        return view('student.submissions.show', compact('assignment', 'submission'));
    }

    public function store(Request $request, Assignment $assignment)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $request->validate([
            'note' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store("submissions/{$student->id}", 'public');
        }

        $submission = Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            [
                'note' => $request->note,
                'file_path' => $filePath ?? null,
                'submitted_at' => now(),
            ]
        );

        return redirect()->route('student.submissions.show', $assignment)->with('success', 'Tugas berhasil dikumpulkan.');
    }
}
