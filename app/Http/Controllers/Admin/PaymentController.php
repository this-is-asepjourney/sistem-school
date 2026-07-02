<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['student.user'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->period, fn ($q) => $q->where('period', $request->period))
            ->when($request->search, fn ($q) => $q->whereHas('student.user', fn ($u) => $u->where('name', 'like', "%{$request->search}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total_paid' => Payment::where('status', 'paid')->sum('amount'),
            'total_pending' => Payment::where('status', 'pending')->sum('amount'),
            'total_overdue' => Payment::where('status', 'overdue')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'summary'));
    }

    public function create()
    {
        $students = Student::with('user')->where('status', 'aktif')->get();

        return view('admin.payments.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'period' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        if ($validated['status'] === 'paid') {
            $validated['paid_at'] = now();
            $validated['received_by'] = auth()->id();
        }

        Payment::create($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function markPaid(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'received_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Tagihan berhasil dihapus.');
    }

    /**
     * Generate tagihan SPP massal untuk semua siswa aktif
     */
    public function generateBulk(Request $request)
    {
        $request->validate([
            'period' => 'required|string|max:7',
            'amount' => 'required|numeric|min:0',
        ]);

        $students = Student::where('status', 'aktif')->get();
        $count = 0;

        foreach ($students as $student) {
            $exists = Payment::where('student_id', $student->id)
                ->where('period', $request->period)
                ->where('type', 'SPP')
                ->exists();

            if (! $exists) {
                Payment::create([
                    'student_id' => $student->id,
                    'type' => 'SPP',
                    'amount' => $request->amount,
                    'period' => $request->period,
                    'status' => 'pending',
                ]);
                $count++;
            }
        }

        return back()->with('success', "Berhasil membuat {$count} tagihan SPP untuk periode {$request->period}.");
    }
}
