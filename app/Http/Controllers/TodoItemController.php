<?php

namespace App\Http\Controllers;

use App\Exceptions\TodoItemAlreadyDoneException;
use App\Exceptions\TodoItemNotFoundException;
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

    public function index()
    {
        $pendent = $this->todoItemService->listPendent();
        $completed = $this->todoItemService->listCompleted();
        return view('todo-items.index', compact([
            'pendent',
            'completed',
        ]));
    }

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
        try {
            $this->todoItemService->markAsDone($id);

            return response()->json([], JsonResponse::HTTP_OK);
        } catch (TodoItemNotFoundException $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (TodoItemAlreadyDoneException $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

}
