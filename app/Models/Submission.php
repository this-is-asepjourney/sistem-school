<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = [
        'assignment_id', 'student_id', 'note', 'file_path',
        'score', 'feedback', 'submitted_at', 'graded_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'submitted_at' => 'datetime',
            'graded_at' => 'datetime',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function isGraded(): bool
    {
        return $this->graded_at !== null;
    }

    public function isLate(): bool
    {
        return $this->submitted_at && $this->assignment->due_date &&
               $this->submitted_at->gt($this->assignment->due_date);
    }
}
