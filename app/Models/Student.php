<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'class_id', 'nis', 'nisn', 'gender',
        'birth_date', 'birth_place', 'address', 'phone',
        'parent_name', 'parent_phone', 'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getNameAttribute(): string
    {
        return $this->user?->name ?? '';
    }

    public function getEmailAttribute(): string
    {
        return $this->user?->email ?? '';
    }

    /**
     * Hitung persentase kehadiran
     */
    public function attendancePercentage(?int $subjectId = null): float
    {
        $query = $this->attendance();
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        $total = $query->count();
        if ($total === 0) {
            return 0;
        }
        $hadir = $query->where('status', 'hadir')->count();

        return round(($hadir / $total) * 100, 1);
    }
}
