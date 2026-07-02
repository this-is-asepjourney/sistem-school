<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::active();
        $classes = ClassRoom::when($activeYear, fn ($q) => $q->where('academic_year_id', $activeYear->id))->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        $query = Attendance::with(['student.user', 'class', 'subject'])
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->subject_id, fn ($q) => $q->where('subject_id', $request->subject_id))
            ->when($request->date_from, fn ($q) => $q->where('date', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->where('date', '<=', $request->date_to))
            ->latest('date');

        $attendances = $query->paginate(30)->withQueryString();

        // Rekap per status untuk tanggal filter
        $recap = [];
        if ($request->class_id) {
            $recap = Attendance::where('class_id', $request->class_id)
                ->when($request->date_from, fn ($q) => $q->where('date', '>=', $request->date_from))
                ->when($request->date_to, fn ($q) => $q->where('date', '<=', $request->date_to))
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        }

        return view('admin.attendance.index', compact('attendances', 'classes', 'subjects', 'recap'));
    }

    /**
     * Export rekap absensi sebagai CSV
     */
    public function export(Request $request)
    {
        $query = Attendance::with(['student.user', 'class', 'subject'])
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->date_from, fn ($q) => $q->where('date', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->where('date', '<=', $request->date_to))
            ->latest('date')
            ->get();

        $filename = 'rekap-absensi-'.now()->format('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Nama Siswa', 'Kelas', 'Mata Pelajaran', 'Status', 'Keterangan']);

            foreach ($query as $row) {
                fputcsv($file, [
                    $row->date->format('d/m/Y'),
                    $row->student?->user?->name,
                    $row->class?->name,
                    $row->subject?->name ?? '-',
                    $row->status_label,
                    $row->note ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
