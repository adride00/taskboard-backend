<?php

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

Route::post('/projects', 'App\Http\Controllers\ProjectsController@store');

Route::post('/users', 'App\Http\Controllers\UserController@store');

Route::get('/tasks', 'App\Http\Controllers\TasksController@index');

Route::post('/tasks', 'App\Http\Controllers\TasksController@store');
