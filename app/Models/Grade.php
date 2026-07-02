<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'academic_year_id', 'semester',
        'type', 'score', 'max_score', 'note', 'graded_by',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'max_score' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function gradedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'uh' => 'Ulangan Harian',
            'uts' => 'UTS',
            'uas' => 'UAS',
            'tugas' => 'Tugas',
            'rapor' => 'Nilai Rapor',
            default => strtoupper($this->type),
        };
    }

    /**
     * Nilai akhir (normalize ke 100)
     */
    public function getNormalizedScoreAttribute(): float
    {
        if (! $this->score || ! $this->max_score) {
            return 0;
        }

        return round(($this->score / $this->max_score) * 100, 2);
    }

    public function getLetterGradeAttribute(): string
    {
        $score = $this->normalized_score;

        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            $score >= 60 => 'D',
            default => 'E',
        };
    }
}
