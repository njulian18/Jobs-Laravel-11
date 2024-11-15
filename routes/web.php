<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobTestController;
use App\Http\Controllers\TestJobController;
use App\Http\Controllers\LogController;


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
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/test-job', [JobTestController::class, 'dispatchJob']);
    Route::get('/dispatch-job', [JobTestController::class, 'dispatchJob']);

    Route::get('/test-job-success', [TestJobController::class, 'testSuccess']);
    Route::get('/test-job-failure', [TestJobController::class, 'testFailure']);
    Route::post('/start-worker', [TestJobController::class, 'startWorker']);
    Route::post('/stop-worker', [TestJobController::class, 'stopWorker']);
    Route::get('/logs', [LogController::class, 'getLogs'])->name('logs.get');

});

require __DIR__.'/auth.php';
