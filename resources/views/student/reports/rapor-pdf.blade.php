<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rapor Siswa — {{ $student?->user?->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10pt; color: #1a1a1a; }
        .page { padding: 20mm 15mm; }

        /* Header */
        .school-header { text-align: center; border-bottom: 3px solid #1a1a6e; padding-bottom: 12px; margin-bottom: 16px; }
        .school-name { font-size: 16pt; font-weight: bold; color: #1a1a6e; text-transform: uppercase; }
        .school-sub { font-size: 9pt; color: #444; margin-top: 3px; }
        .rapor-title { font-size: 13pt; font-weight: bold; text-align: center; background: #1a1a6e; color: white; padding: 6px; margin: 12px 0; letter-spacing: 2px; }

        /* Student info */
        .info-box { border: 1px solid #ccc; padding: 10px 12px; margin-bottom: 14px; }
        .info-table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
        .info-table td { padding: 2px 4px; }
        .info-table td:first-child { font-weight: bold; width: 25%; }
        .info-table td:nth-child(2) { width: 5%; }

        /* Grades table */
        .grades-table { width: 100%; border-collapse: collapse; font-size: 9.5pt; margin-bottom: 14px; }
        .grades-table th { background: #1a1a6e; color: white; padding: 6px 8px; text-align: center; border: 1px solid #1a1a6e; }
        .grades-table td { padding: 5px 8px; border: 1px solid #ddd; }
        .grades-table tr:nth-child(even) td { background: #f5f5f5; }
        .grades-table .score { text-align: center; font-weight: bold; }
        .grades-table .grade-a { color: #16a34a; }
        .grades-table .grade-b { color: #2563eb; }
        .grades-table .grade-c { color: #d97706; }
        .grades-table .grade-d { color: #dc2626; }

        /* Attendance */
        .attendance-box { display: flex; gap: 12px; margin-bottom: 14px; }
        .att-item { flex: 1; border: 1px solid #ddd; border-radius: 4px; padding: 8px; text-align: center; }
        .att-label { font-size: 8pt; color: #666; }
        .att-value { font-size: 14pt; font-weight: bold; }

        /* Signature */
        .signature-section { display: flex; justify-content: space-between; margin-top: 20px; }
        .signature-block { text-align: center; width: 40%; }
        .signature-line { border-top: 1px solid #333; margin-top: 50px; padding-top: 4px; font-size: 9pt; }
    </style>
</head>
<body>
<div class="page">
    {{-- Header Sekolah --}}
    <div class="school-header">
        <div class="school-name">{{ config('app.name') }}</div>
        <div class="school-sub">Jl. Pendidikan No. 1 · Telp. (021) 123-4567</div>
    </div>

    <div class="rapor-title">LAPORAN HASIL BELAJAR SISWA</div>

    {{-- Info Siswa --}}
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td>Nama Siswa</td><td>:</td><td>{{ $student?->user?->name }}</td>
                <td>Kelas</td><td>:</td><td>{{ $student?->class?->name }}</td>
            </tr>
            <tr>
                <td>NIS / NISN</td><td>:</td><td>{{ $student?->nis }} / {{ $student?->nisn ?? '-' }}</td>
                <td>Tahun Pelajaran</td><td>:</td><td>{{ $year?->name }} Semester {{ $semester }}</td>
            </tr>
        </table>
    </div>

    {{-- Tabel Nilai --}}
    <table class="grades-table">
        <thead>
            <tr>
                <th style="width:5%">No.</th>
                <th style="text-align:left">Mata Pelajaran</th>
                <th style="width:12%">Nilai</th>
                <th style="width:10%">Huruf</th>
                <th style="text-align:left; width:30%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grades as $index => $grade)
                @php
                    $score = $grade->normalized_score;
                    $letter = $grade->letter_grade;
                    $colorClass = match($letter) { 'A' => 'grade-a', 'B' => 'grade-b', 'C' => 'grade-c', default => 'grade-d' };
                @endphp
                <tr>
                    <td class="score">{{ $index + 1 }}</td>
                    <td>{{ $grade->subject?->name }}</td>
                    <td class="score {{ $colorClass }}">{{ number_format($grade->score, 0) }}</td>
                    <td class="score {{ $colorClass }}">{{ $letter }}</td>
                    <td>{{ $grade->note ?? ($score >= 75 ? 'Tuntas' : 'Belum Tuntas') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center; padding:16px; color:#999">Belum ada nilai rapor</td></tr>
            @endforelse

            @if($grades->count() > 0)
                <tr>
                    <td colspan="2" style="text-align:right; font-weight:bold; background:#f0f0f0;">Rata-rata</td>
                    <td class="score" style="font-weight:bold; background:#f0f0f0;">{{ number_format($grades->avg('score'), 1) }}</td>
                    <td colspan="2" style="background:#f0f0f0;"></td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Kehadiran --}}
    <p style="font-weight:bold; font-size:10pt; margin-bottom:8px;">Rekap Kehadiran</p>
    <div style="display:flex; gap:12px; margin-bottom:14px; border:1px solid #ddd; padding:10px; border-radius:4px;">
        @foreach(['hadir' => ['Hadir', '#16a34a'], 'sakit' => ['Sakit', '#d97706'], 'izin' => ['Izin', '#2563eb'], 'alpa' => ['Alpa', '#dc2626']] as $key => [$label, $color])
            <div style="flex:1; text-align:center;">
                <div style="font-size:8pt; color:#666;">{{ $label }}</div>
                <div style="font-size:16pt; font-weight:bold; color:{{ $color }};">{{ $attendanceSummary[$key] ?? 0 }}</div>
                <div style="font-size:8pt; color:#666;">hari</div>
            </div>
        @endforeach
    </div>

    {{-- Tanda Tangan --}}
    <div class="signature-section">
        <div class="signature-block">
            <div style="font-size:9pt;">Mengetahui,</div>
            <div style="font-size:9pt; font-weight:bold;">Orang Tua / Wali</div>
            <div class="signature-line">...........................</div>
        </div>
        <div class="signature-block">
            <div style="font-size:9pt;">{{ now()->isoFormat('D MMMM Y') }}</div>
            <div style="font-size:9pt; font-weight:bold;">Wali Kelas</div>
            <div class="signature-line">{{ $student?->class?->homeroomTeacher?->name ?? '...........................' }}</div>
        </div>
    </div>
</div>
</body>
</html>
