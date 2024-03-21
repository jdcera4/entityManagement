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

Route::post('login', [App\Http\Controllers\Api\LoginController::class, 'login']);

use App\Http\Controllers\Api\V1\UsersController;

Route::get('v1/users', [UsersController::class, 'index'])->middleware('auth:sanctum');
Route::post('v1/users', [UsersController::class, 'store'])->middleware('auth:sanctum');
Route::put('v1/users/{id}', [UsersController::class, 'update'])->middleware('auth:sanctum');
Route::get('v1/users/{id}', [UsersController::class, 'show'])->middleware('auth:sanctum');
Route::delete('v1/users/{id}', [UsersController::class, 'destroy'])->middleware('auth:sanctum');

use App\Http\Controllers\Api\V1\ChallengesController;
Route::get('v1/challenges', [ChallengesController::class, 'index'])->middleware('auth:sanctum');
Route::get('v1/challenges/{id}', [ChallengesController::class, 'show'])->middleware('auth:sanctum');
Route::post('v1/challenges', [ChallengesController::class, 'store'])->middleware('auth:sanctum');
Route::put('v1/challenges/{id}', [ChallengesController::class, 'update'])->middleware('auth:sanctum');
Route::delete('v1/challenges/{id}', [ChallengesController::class, 'destroy'])->middleware('auth:sanctum');

use App\Http\Controllers\Api\V1\CompaniesController;

Route::get('v1/companies', [CompaniesController::class, 'index'])->middleware('auth:sanctum');
Route::post('v1/companies', [CompaniesController::class, 'store'])->middleware('auth:sanctum');
Route::put('v1/companies/{id}', [CompaniesController::class, 'update'])->middleware('auth:sanctum');
Route::get('v1/companies/{id}', [CompaniesController::class, 'show'])->middleware('auth:sanctum');
Route::delete('v1/companies/{id}', [CompaniesController::class, 'destroy'])->middleware('auth:sanctum');

use App\Http\Controllers\Api\V1\ProgramsController;

Route::get('v1/programs', [ProgramsController::class, 'index'])->middleware('auth:sanctum');
Route::post('v1/programs', [ProgramsController::class, 'store'])->middleware('auth:sanctum');
Route::put('v1/programs/{id}', [ProgramsController::class, 'update'])->middleware('auth:sanctum');
Route::get('v1/programs/{id}', [ProgramsController::class, 'show'])->middleware('auth:sanctum');
Route::delete('v1/programs/{id}', [ProgramsController::class, 'destroy'])->middleware('auth:sanctum');

use App\Http\Controllers\Api\V1\ProgramsParticipantsController;

Route::get('v1/programs-participants', [ProgramsParticipantsController::class, 'index'])->middleware('auth:sanctum');
Route::post('v1/programs-participants', [ProgramsParticipantsController::class, 'store'])->middleware('auth:sanctum');
Route::put('v1/programs-participants/{id}', [ProgramsParticipantsController::class, 'update'])->middleware('auth:sanctum');
Route::get('v1/programs-participants/{id}', [ProgramsParticipantsController::class, 'show'])->middleware('auth:sanctum');
Route::delete('v1/programs-participants/{id}', [ProgramsParticipantsController::class, 'destroy'])->middleware('auth:sanctum');

use App\Http\Controllers\Api\ProcessGtpController;

Route::get('/process-gpt', [ProcessGtpController::class, 'data']);
