<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Grade;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function grades(Request $request)
    {
        $student = auth()->user()->student?->load(['class', 'user']);
        $activeYear = AcademicYear::active();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        $yearId = $request->academic_year_id ?? $activeYear?->id;
        $semester = $request->semester ?? 1;

        $grades = Grade::where('student_id', $student?->id)
            ->where('academic_year_id', $yearId)
            ->where('semester', $semester)
            ->with('subject')
            ->get()
            ->groupBy('subject_id');

        return view('student.reports.grades', compact('student', 'grades', 'academicYears', 'yearId', 'semester'));
    }

    public function attendance(Request $request)
    {
        $student = auth()->user()->student?->load(['class', 'user']);

        $attendances = $student?->attendance()
            ->with('subject')
            ->when($request->date_from, fn ($q) => $q->where('date', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->where('date', '<=', $request->date_to))
            ->latest('date')
            ->paginate(30)
            ->withQueryString();

        $statusSummary = $student?->attendance()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('student.reports.attendance', compact('student', 'attendances', 'statusSummary'));
    }

    public function payments()
    {
        $student = auth()->user()->student;

        $payments = $student?->payments()
            ->latest()
            ->paginate(20);

        $totalPaid = $student?->payments()->where('status', 'paid')->sum('amount');
        $totalPending = $student?->payments()->where('status', 'pending')->sum('amount');

        return view('student.reports.payments', compact('student', 'payments', 'totalPaid', 'totalPending'));
    }

    /**
     * Generate PDF rapor semester
     */
    public function downloadRapor(Request $request)
    {
        $student = auth()->user()->student?->load(['class.academicYear', 'user']);
        $activeYear = AcademicYear::active();

        $yearId = $request->academic_year_id ?? $activeYear?->id;
        $semester = $request->semester ?? 1;
        $year = AcademicYear::find($yearId);

        $grades = Grade::where('student_id', $student?->id)
            ->where('academic_year_id', $yearId)
            ->where('semester', $semester)
            ->where('type', 'rapor')
            ->with('subject')
            ->get();

        $attendanceSummary = $student?->attendance()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pdf = Pdf::loadView('student.reports.rapor-pdf', compact(
            'student', 'grades', 'year', 'semester', 'attendanceSummary'
        ));

        $pdf->setPaper('A4', 'portrait');

        $filename = "Rapor-{$student?->user?->name}-Sem{$semester}-{$year?->name}.pdf";

        return $pdf->download($filename);
    }
}
