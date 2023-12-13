<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoItemRequest;
use App\Services\TodoItemService;
use Illuminate\Http\JsonResponse;

class TodoItemController extends Controller
{
    protected readonly TodoItemService $todoItemService;

    public function __construct()
    {
        $this->todoItemService = new TodoItemService;
    }

    public function index() {}

    public function create() {}

    public function store (StoreTodoItemRequest $request): JsonResponse
    {
        try {
            $todoItem = $this->todoItemService->store($request->validated());

            return response()->json($todoItem, JsonResponse::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show() {}

    public function edit() {}

    public function update() {}

    public function destroy() {}

    public function markAsDone (int $id)
    {
    }

}