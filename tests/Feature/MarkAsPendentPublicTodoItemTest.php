<?php

namespace Tests\Feature;

use App\Models\TodoItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class MarkAsPendentPublicTodoItemTest extends TestCase
{
    public function testMarkAValidTodoItemAsPendent(): void
    {
        // Arrange
        $item = TodoItem::factory()->create([
            'status'       => TodoItem::STATUS_DONE,
            'completed_at' => null,
        ]);
        $this->assertDatabaseHas(TodoItem::class, [
            'id'          => $item->getKey(),
            'status'      => TodoItem::STATUS_DONE,
        ]);

        // act
        $response = $this->post(route('app.todo.item.mark-pendent', [
            'id' => $item->getKey(),
        ]));

        // assert
        $response->assertStatus(JsonResponse::HTTP_OK);
        $this->assertDatabaseHas(TodoItem::class, [
            'id'          => $item->getKey(),
            'status'      => TodoItem::STATUS_PENDENT,
        ]);
        $this->assertDatabaseMissing(TodoItem::class, [
            'id'           => $item->getKey(),
            'completed_at' => null,
            'status'       => $item->status,
        ]);
    }

    public function testShouldReturnNotFoundWhenTriedToMarkANotFoundItemAsPendent(): void
    {
        // Arrange
        $id = 5000;

        // act
        $response = $this->post(route('app.todo.item.mark-pendent', compact('id')));

        // assert
        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
        $this->assertDatabaseMissing(TodoItem::class, [
            'id'           => $id,
        ]);
    }

    public function testShouldReturnUnprocessableWhenTriedToMarkAPendentTodoItem(): void
    {
        // Arrange
        $item = TodoItem::factory()->create([
            'status'       => TodoItem::STATUS_PENDENT,
            'completed_at' => null,
        ]);

        // act
        $response = $this->post(route('app.todo.item.mark-pendent', [
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
