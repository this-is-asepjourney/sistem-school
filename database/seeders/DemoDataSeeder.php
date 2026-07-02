<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Tahun Ajaran Aktif ────────────────────────────────────
        $year = AcademicYear::create([
            'name' => '2024/2025',
            'semester' => 1,
            'start_date' => '2024-07-15',
            'end_date' => '2024-12-20',
            'is_active' => true,
        ]);

        // ── Mata Pelajaran ────────────────────────────────────────
        $subjects = [
            ['name' => 'Matematika', 'code' => 'MTK', 'grade_level' => 7, 'hours_per_week' => 4],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND', 'grade_level' => 7, 'hours_per_week' => 4],
            ['name' => 'Bahasa Inggris', 'code' => 'BING', 'grade_level' => 7, 'hours_per_week' => 4],
            ['name' => 'IPA', 'code' => 'IPA', 'grade_level' => 7, 'hours_per_week' => 5],
            ['name' => 'IPS', 'code' => 'IPS', 'grade_level' => 7, 'hours_per_week' => 3],
            ['name' => 'PPKN', 'code' => 'PKN', 'grade_level' => 7, 'hours_per_week' => 2],
            ['name' => 'Seni Budaya', 'code' => 'SBD', 'grade_level' => 7, 'hours_per_week' => 2],
            ['name' => 'Penjas', 'code' => 'PJOK', 'grade_level' => 7, 'hours_per_week' => 2],
        ];

        $createdSubjects = [];
        foreach ($subjects as $s) {
            $createdSubjects[] = Subject::create($s);
        }

        // ── Guru ──────────────────────────────────────────────────
        $guruData = [
            ['name' => 'Budi Santoso', 'email' => 'budi@sekolah.id', 'nip' => '198001012006041001'],
            ['name' => 'Siti Aminah', 'email' => 'siti@sekolah.id', 'nip' => '198203052008022001'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@sekolah.id', 'nip' => '197905152005011002'],
        ];

        $teachers = [];
        foreach ($guruData as $index => $g) {
            $user = User::create([
                'name' => $g['name'],
                'email' => $g['email'],
                'password' => Hash::make('password'),
                'role' => 'guru',
                'is_active' => true,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'nip' => $g['nip'],
                'gender' => $index % 2 === 0 ? 'L' : 'P',
                'education' => 'S1',
                'status' => 'aktif',
            ]);

            // Assign 2-3 mapel ke setiap guru
            $assignedSubjects = array_slice($createdSubjects, $index * 2, 3);
            foreach ($assignedSubjects as $subj) {
                DB::table('subject_teacher')->insert([
                    'user_id' => $user->id,
                    'subject_id' => $subj->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $teachers[] = ['user' => $user, 'teacher' => $teacher];
        }

        // ── Kelas ─────────────────────────────────────────────────
        $classNames = ['VII A', 'VII B', 'VII C'];
        $classes = [];
        foreach ($classNames as $index => $name) {
            $classes[] = ClassRoom::create([
                'academic_year_id' => $year->id,
                'name' => $name,
                'grade_level' => 7,
                'homeroom_teacher_id' => $teachers[$index]['user']->id,
            ]);
        }

        // ── Siswa ─────────────────────────────────────────────────
        $siswaNames = [
            'Andi Pratama', 'Bella Safitri', 'Candra Putra', 'Dewi Lestari', 'Eko Santoso',
            'Fitri Handayani', 'Guntur Wibowo', 'Hana Pertiwi', 'Irwan Susanto', 'Jeni Rahayu',
        ];

        $nisCounter = 2024001;
        foreach ($siswaNames as $index => $name) {
            $classId = $classes[$index % 3]->id;
            $email = strtolower(str_replace(' ', '.', $name)).'@siswa.id';

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'is_active' => true,
            ]);

            Student::create([
                'user_id' => $user->id,
                'class_id' => $classId,
                'nis' => (string) $nisCounter++,
                'gender' => $index % 2 === 0 ? 'L' : 'P',
                'birth_date' => '2011-0'.($index + 1).'-15',
                'status' => 'aktif',
            ]);
        }

        $this->command->info('Demo data seeded: 1 tahun ajaran, '.count($subjects).' mapel, '.count($guruData).' guru, '.count($classNames).' kelas, '.count($siswaNames).' siswa.');
    }
}
