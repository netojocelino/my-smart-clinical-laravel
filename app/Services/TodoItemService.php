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

    public function listPendent ()
    {
        return $this->todoItem->wherePendent()->get();
    }

    public function listCompleted ()
    {
        return $this->todoItem->whereDone()->get();
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
            throw new TodoItemNotFoundException(__('messages.not_found'));
        }
        return $todoItem;
    }

    public function markAsDone(int $id)
    {
        $todoItem = $this->findOrFail($id);

        if ($todoItem->isComplete) {
            throw new TodoItemAlreadyDoneException(__('messages.already_competed'));
        }

        $todoItem->update([
            'status'       => TodoItem::STATUS_DONE,
            'completed_at' => date('Y-m-d H:i:s'),
        ]);

        return $todoItem;
    }

    public function markAsPendent(int $id)
    {
        $todoItem = $this->findOrFail($id);

        if (!$todoItem->isComplete) {
            throw new TodoItemAlreadyDoneException(__('messages.already_pendent'));
        }

        $todoItem->update([
            'status'       => TodoItem::STATUS_PENDENT,
            'completed_at' => null,
        ]);

        return $todoItem;
    }

}
