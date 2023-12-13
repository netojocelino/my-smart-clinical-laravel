<?php

use App\Http\Controllers\TodoItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('todo-item', TodoItemController::class)->names('app.todo.item');

Route::post('/todo-item/{id}', [TodoItemController::class, 'markAsDone'])->name('app.todo.item.mark-done');
