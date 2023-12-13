<?php

namespace Tests\Feature;

use App\Models\TodoItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class MarkAsDonePublicTodoItemTest extends TestCase
{
    public function testMarkAValidTodoItemAsDone(): void
    {
        // Arrange
        $item = TodoItem::factory()->create([
            'status'       => 'pendent',
            'completed_at' => null,
        ]);
        $this->assertDatabaseHas(TodoItem::class, [
            'id'          => $item->getKey(),
            'status'      => 'pendent',
        ]);

        // act
        $response = $this->post(route('app.todo.item.mark-done', [
            'id' => $item->getKey(),
        ]));

        // assert
        $response->assertStatus(JsonResponse::HTTP_OK);
        $this->assertDatabaseHas(TodoItem::class, [
            'id'          => $item->getKey(),
            'status'      => 'done',
        ]);
        $this->assertDatabaseMissing(TodoItem::class, [
            'id'           => $item->getKey(),
            'completed_at' => null,
            'status'       => $item->status,
        ]);
    }
}
