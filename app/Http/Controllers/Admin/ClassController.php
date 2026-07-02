<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::with(['academicYear', 'homeroomTeacher', 'students'])
            ->latest()
            ->paginate(20);
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        return view('admin.classes.index', compact('classes', 'academicYears'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $teachers = User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get();

        return view('admin.classes.create', compact('academicYears', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:50',
            'grade_level' => 'required|integer|min:1|max:12',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ]);

        ClassRoom::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(ClassRoom $class)
    {
        $class->load(['academicYear', 'homeroomTeacher', 'students.user', 'subjects']);

        return view('admin.classes.show', compact('class'));
    }

    public function edit(ClassRoom $class)
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $teachers = User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::where('grade_level', $class->grade_level)->orderBy('name')->get();

        return view('admin.classes.edit', compact('class', 'academicYears', 'teachers', 'subjects'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:50',
            'grade_level' => 'required|integer|min:1|max:12',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
