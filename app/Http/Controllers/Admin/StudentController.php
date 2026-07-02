<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'class'])
            ->when($request->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$request->search}%")))
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest();

        $students = $query->paginate(20)->withQueryString();
        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nis' => 'nullable|unique:students,nis',
            'nisn' => 'nullable|string|max:20',
            'class_id' => 'nullable|exists:classes,id',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'username' => $validated['nis'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'siswa',
                'is_active' => true,
                'must_change_password' => true,
            ]);

            Student::create([
                'user_id' => $user->id,
                'class_id' => $validated['class_id'] ?? null,
                'nis' => $validated['nis'] ?? null,
                'nisn' => $validated['nisn'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'birth_place' => $validated['birth_place'] ?? null,
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'parent_name' => $validated['parent_name'] ?? null,
                'parent_phone' => $validated['parent_phone'] ?? null,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'class', 'grades.subject', 'attendance', 'payments']);

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($student->user_id)],
            'nis' => ['nullable', Rule::unique('students', 'nis')->ignore($student->id)],
            'nisn' => 'nullable|string|max:20',
            'class_id' => 'nullable|exists:classes,id',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,lulus,keluar',
        ]);

        DB::transaction(function () use ($validated, $student) {
            $student->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $student->update(collect($validated)->except(['name', 'email'])->toArray());
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $userId = $student->user_id;
            $student->delete();
            User::destroy($userId);
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dihapus.');
    }

    /**
     * Import siswa dari CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $rows = array_map('str_getcsv', file($file->getPathname()));
        $header = array_shift($rows);
        $header = array_map('trim', $header);

        $imported = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $data = array_combine($header, $row);
            $rowNum = $index + 2;

            try {
                DB::transaction(function () use ($data) {
                    $tanggalLahir = $data['tanggal_lahir'] ?? null;
                    $password = $tanggalLahir 
                        ? date('Ymd', strtotime($tanggalLahir)) 
                        : 'password123';

                    $user = User::create([
                        'name' => $data['nama'] ?? 'Tanpa Nama',
                        'email' => $data['email'] ?? null,
                        'username' => $data['nis'] ?? null,
                        'password' => Hash::make($password),
                        'role' => 'siswa',
                        'is_active' => true,
                        'must_change_password' => true,
                    ]);

                    $class = isset($data['kelas'])
                        ? ClassRoom::where('name', $data['kelas'])->first()
                        : null;

                    Student::create([
                        'user_id' => $user->id,
                        'class_id' => $class?->id,
                        'nis' => $data['nis'] ?? null,
                        'birth_date' => $tanggalLahir,
                        'phone' => $data['nomor_hp'] ?? null,
                    ]);
                });
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNum}: {$e->getMessage()}";
            }
        }

        $message = "Berhasil import {$imported} siswa.";
        if (! empty($errors)) {
            $message .= ' Gagal: '.implode(', ', array_slice($errors, 0, 3));
        }

        return redirect()->route('admin.students.index')->with('success', $message);
    }
}
