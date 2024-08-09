<?php

use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TasksController::class, 'index'])->name('tasks.index');
Route::post('/task', [TasksController::class, 'store'])->name('tasks.store');
Route::get('/fetch-todo-list', [TasksController::class, 'fetchTodoList']);
Route::get('/display-todo-list', [TasksController::class, 'displayTodoList']);
Route::post('/check-user', [TasksController::class, 'checkUser']);