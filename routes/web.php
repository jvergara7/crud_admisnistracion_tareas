<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
Route::resource('/task', App\Http\Controllers\TaskController::class)->middleware(['auth:sanctum', 'verified']);
Route::middleware(['auth:sanctum', 'verified'])->get('/report', [TaskController::class, 'report'])->name('report');
Route::middleware(['auth:sanctum', 'verified'])->get('/files/{fileId}', [TaskController::class, 'downloadFile'])->name('download');
Route::middleware(['auth:sanctum', 'verified'])->delete('/task/deleteFile/{id}',  [TaskController::class, 'deleteFile'])->name('task.deleteFile');
Route::middleware(['auth:sanctum', 'verified'])->post('/task/{task}/complete', [TaskController::class, 'completeTask'])->name('task.complete');
