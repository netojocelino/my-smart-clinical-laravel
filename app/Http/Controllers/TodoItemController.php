<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoItemRequest;
use App\Services\TodoItemService;
use Illuminate\Http\JsonResponse;

class TodoItemController extends Controller
{

    public function index() {}

    public function store (Request $request)
    {
        TodoItem::create($request->all());
        return response()->json([], JsonResponse::HTTP_CREATED);
    }
}
