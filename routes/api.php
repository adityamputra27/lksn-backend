<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\ScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    //auth
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::get('logout', [AuthController::class, 'logout'])->middleware('authToken');
    });

    Route::middleware(['authToken', 'admin'])->group(function () {
        //branch
        Route::resource('branches', BranchController::class);
        //movie
        Route::resource('movies', MovieController::class);
        //studio
        Route::resource('studios', StudioController::class);
        //schedule
        Route::resource('schedules', ScheduleController::class);
    });

    //view schedule
    Route::post('available-schedules', [ScheduleController::class, 'viewSchedules'])->middleware('authToken');
});
