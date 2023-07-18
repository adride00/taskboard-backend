<?php

use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/tasks', [TasksController::class, 'index']);
Route::get('/tasks/{id}', [TasksController::class, 'show']);
Route::put('/tasks/{id}', [TasksController::class, 'update']);
Route::put('/deleteTask/{id}', [TasksController::class, 'softDelete']);
Route::put('/updateProgress/{id}', [TasksController::class, 'updateProgress']);
Route::put('/tasks', [TasksController::class, 'store']);
Route::get('/searchTasks/{search}', [TasksController::class, 'searchTask']);

//Users Routes
Route::get('/users', [UsersController::class, 'index']);
Route::post('/users',[UsersController::class, 'store']);
Route::get('/users/{id}', [UsersController::class,'show']);
Route::put('/users/{id}', [UsersController::class, 'update']);
Route::put('/users/{id}', [UsersController::class, 'destroy']);

