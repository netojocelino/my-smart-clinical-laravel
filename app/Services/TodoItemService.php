<?php

namespace App\Services;

use App\Exceptions\TodoItemAlreadyDoneException;
use App\Exceptions\TodoItemNotFoundException;
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

    public function find(int $id)
    {
        return $this->todoItem->find($id);
    }

    public function findOrFail(int $id)
    {
        $todoItem = $this->todoItem->find($id);

        if (!$todoItem) {
            throw new TodoItemNotFoundException('Todo Item was not found');
        }
        return $todoItem;
    }

    public function markAsDone(int $id)
    {
        $todoItem = $this->findOrFail($id);

        if ($todoItem->isComplete) {
            throw new TodoItemAlreadyDoneException('Todo Item it\'s already completed');
        }

        $todoItem->update([
            'status'       => TodoItem::STATUS_DONE,
            'completed_at' => date('Y-m-d H:i:s'),
        ]);

        return $todoItem;
    }
}
