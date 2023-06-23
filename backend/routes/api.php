<?php

use App\Http\Controllers\Api;
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

// GET

Route::get('/getUser', [App\Http\Controllers\Api::class, 'getUserById']);
Route::get('/authUser', [App\Http\Controllers\Api::class, 'authUser']);
Route::get('/getUserByToken', [App\Http\Controllers\Api::class, 'getUserByToken']);
Route::get('/getComments', [App\Http\Controllers\Api::class, 'getComments']);
Route::get('/getUserPlaylists', [App\Http\Controllers\Api::class, 'getUserPlaylists']);

// POST

Route::post('/createUser', [App\Http\Controllers\Api::class, 'createUser']);

Route::post('/setWatchLater', [App\Http\Controllers\Api::class, 'setUserWatchLater']);
Route::post('/delWatchLater', [App\Http\Controllers\Api::class, 'delUserWatchLater']);

Route::post('/setComment', [App\Http\Controllers\Api::class, 'setComment']);
Route::post('/delComment', [App\Http\Controllers\Api::class, 'delComment']);
Route::post('/editComment', [App\Http\Controllers\Api::class, 'editComment']);

Route::post('/setPlaylist', [App\Http\Controllers\Api::class, 'setPlaylist']);
Route::post('/delPlaylist', [App\Http\Controllers\Api::class, 'delPlaylist']);
Route::post('/updateNamePlaylist', [App\Http\Controllers\Api::class, 'updateNamePlaylist']);
Route::post('/setFilmInPlaylist', [App\Http\Controllers\Api::class, 'setFilmInPlaylist']);
Route::post('/delFilmFromPlaylist', [App\Http\Controllers\Api::class, 'delFilmFromPlaylist']);
Route::post('/updateFilmInPlaylist', [App\Http\Controllers\Api::class, 'updateFilmInPlaylist']);
Route::post('/moveFilmToAnotherPlaylist', [App\Http\Controllers\Api::class, 'moveFilmToAnotherPlaylist']);