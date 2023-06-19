<?php

use App\Http\Controllers\CountyController;
use App\Types\Enums\StateCodeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Enum;

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
Route::prefix('v1')->group(function () {
    Route::get('/county/{state_code}/', [CountyController::class, 'index'])
        ->where(['state_code' => '^[A-Za-z]{2}$\z'])
        ->name('get_county');
});
