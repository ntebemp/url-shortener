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

use App\Http\Controllers\Api\UrlShortenerController;

Route::post('/shorten', [UrlShortenerController::class, 'shorten']);
Route::get('/{shortCode}', [UrlShortenerController::class, 'redirect']);
Route::get('/stats/{code}', [UrlShortenerController::class, 'stats']);