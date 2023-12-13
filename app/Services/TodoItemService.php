<?php

namespace App\Services;

use App\Models\TodoItem;
use Illuminate\Support\Str;

class TodoItemService
{
    protected readonly TodoItem $todoItem;

    public function __construct ()
    {
        $this->todoItem = new TodoItem();
    }

    public function store (array $data)
    {
        return $this->todoItem->create([
            'title'        => data_get($data, 'title', ''),
            'description'  => data_get($data, 'description', '') ?: '',
            'status'       => 'pendent',
            'completed_at' => null,
        ]);
    }
}