<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkStampController;
use App\Http\Controllers\BreakStampController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return view('auth.login');
});

require __DIR__.'/auth.php';

Route::middleware(['verified'])->group(function(){
    Route::get('/stamp', [WorkStampController::class, 'index'])->name('index');

    Route::post('/start', [WorkStampController::class, 'startTime'])->name('stamp');
    Route::post('/end', [WorkStampController::class, 'endTime'])->name('stamp');
    Route::post('/break/start', [BreakStampController::class, 'startBreak'])->name('stamp');
    Route::post('/break/end', [BreakStampController::class, 'endBreak'])->name('stamp');

    Route::get('/attendance', [WorkStampController::class, 'attendance'])->name('attendance');
    Route::post('/attendance', [WorkStampController::class, 'attendance'])->name('attendance');
    Route::get('/attendance/{num}', [WorkStampController::class, 'attendance'])->name('attendance');
});

Route::get('/logout', [WorkStampController::class, 'logout']);