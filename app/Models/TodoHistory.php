<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodoHistory extends Model
{
    protected $fillable = [
        'todo_item_id',
        'event',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];


    public function TodoItem() : BelongsTo
    {
        return $this->belongsTo(TodoItem::class, 'todo_item_id', 'id');
    }

    public function getEventNameAttribute ()
    {
        return __('enum.events.'.$this->event);
    }

    public function getEventChangesAttribute ()
    {
        return json_decode(json_encode($this->changes));
    }

}
