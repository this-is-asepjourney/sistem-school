<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'created_by', 'title', 'content', 'target', 'class_id', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function getTargetLabelAttribute(): string
    {
        return match ($this->target) {
            'all' => 'Semua',
            'admin' => 'Admin/TU',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
            default => $this->target,
        };
    }
}
