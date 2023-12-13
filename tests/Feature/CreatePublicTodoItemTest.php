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
            'description' => data_get($data, 'description', ''),
            'status'      => data_get($data, 'status'),
            'completed_at' => null,
        ]);
    }

    public function testMustNotCreateAItemForDataWithoutTitle(): void
    {
        // Arrange
        $data = TodoItem::factory()->make([
            'title'        => null,
            'status'       => 'pendent',
            'completed_at' => null,
        ])->toArray();

        // act
        $response = $this->post(route('app.todo.item.store', $data));

        // assert
        $response->assertStatus(JsonResponse::HTTP_FOUND);
        $response->assertSessionHasErrors([
            'title' => 'The title field is required.',
        ]);
        $this->assertDatabaseMissing(TodoItem::class, [
            'title'        => data_get($data, 'title'),
            'description'  => data_get($data, 'description', ''),
            'status'       => data_get($data, 'status'),
            'completed_at' => data_get($data, 'completed_at'),
        ]);
    }

    public function testShouldCreateAItemWithoutDescription(): void
    {
        // Arrange
        $data = TodoItem::factory()->make([
            'description'  => '',
            'status'       => 'pendent',
            'completed_at' => null,
        ])->toArray();

        // act
        $response = $this->post(route('app.todo.item.store', $data));

        // assert
        $response->assertStatus(JsonResponse::HTTP_CREATED);
        $this->assertDatabaseHas(TodoItem::class, [
            'title'        => data_get($data, 'title'),
            'description'  => data_get($data, 'description', ''),
            'status'       => data_get($data, 'status'),
            'completed_at' => data_get($data, 'completed_at'),
        ]);
    }

    public function testShouldCreateAItemWithoutStatus(): void
    {
        // Arrange
        $data = TodoItem::factory()->make([
            'description'  => '',
            'status'       => null,
            'completed_at' => null,
        ])->toArray();

        // act
        $response = $this->post(route('app.todo.item.store', $data));

        // assert
        $response->assertStatus(JsonResponse::HTTP_CREATED);
        $this->assertDatabaseHas(TodoItem::class, [
            'title'        => data_get($data, 'title'),
            'description'  => data_get($data, 'description', ''),
            'status'       => data_get($data, 'status') ?: 'pendent',
            'completed_at' => data_get($data, 'completed_at'),
        ]);
    }
}
