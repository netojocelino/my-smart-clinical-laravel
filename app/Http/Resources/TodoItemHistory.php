<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoItemHistory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $changes = is_string($this->changes) ? $this->changes : json_encode($this->changes);
        $changes = json_decode($changes);

        return [
            'taskName'   => $this->todoItem->title,
            'id'         => $this->id,
            'event'      => __('enum.events.'.$this->event),
            'changes'    => $changes,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
        ];
    }
}
