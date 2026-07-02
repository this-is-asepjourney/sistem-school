<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $assignments = Assignment::where('created_by', $user->id)
            ->with(['subject', 'class'])
            ->withCount('submissions')
            ->latest()
            ->paginate(15);

        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $user = auth()->user();
        $subjects = Subject::whereHas('teachers', fn ($q) => $q->where('users.id', $user->id))->get();
        $classes = ClassRoom::whereHas('subjects', fn ($q) => $q->where('class_subject.user_id', $user->id))->get();

        return view('teacher.assignments.create', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'max_score' => 'required|numeric|min:0|max:1000',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('assignments', 'public');
        }

        $validated['created_by'] = auth()->id();

        Assignment::create($validated);

        return redirect()->route('teacher.assignments.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $assignment->load(['subject', 'class', 'submissions.student.user']);

        return view('teacher.assignments.show', compact('assignment'));
    }

    public function gradeSubmission(Request $request, Submission $submission)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:'.$submission->assignment->max_score,
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'graded_at' => now(),
        ]);

        return back()->with('success', 'Nilai tugas berhasil disimpan.');
    }

    public function destroy(Assignment $assignment)
    {
        $this->authorize('delete', $assignment);
        $assignment->delete();

        return redirect()->route('teacher.assignments.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
