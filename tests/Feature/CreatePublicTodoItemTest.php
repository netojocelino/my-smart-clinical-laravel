<?php

namespace Tests\Feature;

use App\Models\TodoItem;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class CreatePublicTodoItemTest extends TestCase
{
    public function testShouldCreateAItemForValidData(): void
    {
        // Arrange
        $data = TodoItem::factory()->make([
            'status'       => 'pendent',
            'completed_at' => null,
        ])->toArray();

        // act
        $response = $this->post(route('app.todo.item.store', $data));

        // assert
        $response->assertStatus(JsonResponse::HTTP_CREATED);
        $this->assertDatabaseHas(TodoItem::class, [
            'title'       => data_get($data, 'title'),
            'description' => data_get($data, 'description'),
            'status'      => data_get($data, 'status'),
            'completed_at' => null,
        ]);
    }
}
