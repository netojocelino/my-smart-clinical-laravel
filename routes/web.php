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
    return redirect(route('app.todo.item.index'));
});

Route::resource('todo-item', TodoItemController::class)->names('app.todo.item');

Route::post('/todo-item/{id}', [TodoItemController::class, 'markAsDone'])->name('app.todo.item.mark-done');

Route::get('/dev/github', function () {
    return redirect()->to('https://github.com/netojocelino');
})->name('dev.github');

Route::get('/dev/linkedin', function () {
    return redirect()->to('https://www.linkedin.com/in/netojocelino');
})->name('dev.linkedin');
