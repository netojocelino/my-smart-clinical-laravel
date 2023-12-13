<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    use HasFactory;

    public const STATUS_DONE    = 'done';
    public const STATUS_PENDENT = 'pendent';

    protected $fillable = [
        'title',
        'description',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function scopeWherePendent (Builder $query)
    {
        return $query->where('status', self::STATUS_PENDENT);
    }

    public function scopeWhereDone (Builder $query)
    {
        return $query->where('status', self::STATUS_DONE);
    }

    public function getIsCompleteAttribute ()
    {
        return $this->status == self::STATUS_DONE;
    }

    public function getShortDescriptionAttribute ()
    {
        $max = 60;
        $tail = strlen($this->description ?? '') > $max ? '...' : '';
        return substr($this->description, 0, $max) . $tail;
    }
}
