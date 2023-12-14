<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected static function boot ()
    {
        parent::boot();

        parent::created(function ($model) {
            $model->history()->create([
                'event'   => 'created',
                'changes' => '{}',
            ]);
        });

        parent::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            $model->history()->create([
                'event'   => 'updated',
                'changes' => json_encode($changes),
            ]);
        });
    }

    public function scopeWherePendent (Builder $query)
    {
        return $query->where('status', self::STATUS_PENDENT);
    }

    public function scopeWhereDone (Builder $query)
    {
        return $query->where('status', self::STATUS_DONE);
    }

    public function history(): HasMany
    {
        return $this->hasMany(TodoHistory::class, 'todo_item_id', 'id');
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

    public function getHistoryArrayAttribute ()
    {
        return json_decode($this->history, true);
    }

}
