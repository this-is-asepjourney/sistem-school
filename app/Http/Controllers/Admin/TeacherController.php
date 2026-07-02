<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'subjects'])
            ->when($request->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$request->search}%")))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest();

        $teachers = $query->paginate(20)->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();

        return view('admin.teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nip' => 'nullable|unique:teachers,nip',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'education' => 'nullable|string|max:50',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'username' => $validated['nip'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'guru',
                'is_active' => true,
                'must_change_password' => true,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'education' => $validated['education'] ?? null,
            ]);

            if (! empty($validated['subjects'])) {
                foreach ($validated['subjects'] as $subjectId) {
                    DB::table('subject_teacher')->insert([
                        'user_id' => $user->id,
                        'subject_id' => $subjectId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects', 'homeroomClass']);

        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $subjects = Subject::orderBy('name')->get();
        $selectedSubjects = $teacher->subjects->pluck('id')->toArray();

        return view('admin.teachers.edit', compact('teacher', 'subjects', 'selectedSubjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($teacher->user_id)],
            'nip' => ['nullable', Rule::unique('teachers', 'nip')->ignore($teacher->id)],
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'education' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        DB::transaction(function () use ($validated, $teacher) {
            $teacher->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $teacher->update(collect($validated)->except(['name', 'email', 'subjects'])->toArray());

            // Sync subjects
            DB::table('subject_teacher')->where('user_id', $teacher->user_id)->delete();
            if (! empty($validated['subjects'])) {
                foreach ($validated['subjects'] as $subjectId) {
                    DB::table('subject_teacher')->insert([
                        'user_id' => $teacher->user_id,
                        'subject_id' => $subjectId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {
            $userId = $teacher->user_id;
            $teacher->delete();
            User::destroy($userId);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}
