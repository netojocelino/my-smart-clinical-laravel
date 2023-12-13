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
            'status'       => TodoItem::STATUS_PENDENT,
            'completed_at' => null,
        ]);
        $this->assertDatabaseHas(TodoItem::class, [
            'id'          => $item->getKey(),
            'status'      => TodoItem::STATUS_PENDENT,
        ]);

        // act
        $response = $this->post(route('app.todo.item.mark-done', [
            'id' => $item->getKey(),
        ]));

        // assert
        $response->assertStatus(JsonResponse::HTTP_OK);
        $this->assertDatabaseHas(TodoItem::class, [
            'id'          => $item->getKey(),
            'status'      => TodoItem::STATUS_DONE,
        ]);
        $this->assertDatabaseMissing(TodoItem::class, [
            'id'           => $item->getKey(),
            'completed_at' => null,
            'status'       => $item->status,
        ]);
    }

    public function testShouldReturnNotFoundWhenTriedToMarkANotFoundItemAsComepleted(): void
    {
        // Arrange
        $id = 5000;

        // act
        $response = $this->post(route('app.todo.item.mark-done', compact('id')));

        // assert
        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
        $this->assertDatabaseMissing(TodoItem::class, [
            'id'           => $id,
        ]);
    }

    public function testShouldReturnUnprocessableWhenTriedToMarkACompletedTodoItem(): void
    {
        // Arrange
        $item = TodoItem::factory()->create([
            'status'       => TodoItem::STATUS_DONE,
            'completed_at' => date('Y-m-d H:i:s'),
        ]);

        // act
        $response = $this->post(route('app.todo.item.mark-done', [
            'id' => $item->id,
        ]));

        // assert
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseHas(TodoItem::class, [
            'id'           => $item->id,
            'status'       => $item->status,
            'completed_at' => $item->completed_at,
        ]);
    }
}
