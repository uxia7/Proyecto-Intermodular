<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me',    [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Posts
    Route::get('/timeline', [PostController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    // Teorías y música
    Route::get('/theories', [PostController::class, 'theories']);
    Route::get('/music',    [PostController::class, 'music']);

    // Seguidores
    Route::post('/follow/{id}',   [FollowController::class, 'follow']);
    Route::delete('/follow/{id}', [FollowController::class, 'unfollow']);
    Route::get('/followers/{id}', [FollowController::class, 'followers']);
    Route::get('/following/{id}', [FollowController::class, 'following']);

    // Route::put('/user/{id}/bio', [UserController::class, 'updateBio'])->middleware('auth:sanctum');
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::put('/user/{id}/bio', [UserController::class, 'updateBio']);

    Route::post('/posts/{id}/react', [PostController::class, 'react'])->middleware('auth:sanctum');
    Route::get('/posts/{id}/reactions', [PostController::class, 'reactions']);

    Route::get('/posts/{id}/comments', [PostController::class, 'comments']);
    Route::post('/posts/{id}/comments', [PostController::class, 'addComment'])->middleware('auth:sanctum');

    // Obtener un post concreto
    Route::get('/posts/{id}', [PostController::class, 'show']);

    // Obtener comentarios de un post
    Route::get('/posts/{id}/comments', [PostController::class, 'comments']);

    // Añadir comentario (requiere estar logueado)
    Route::post('/posts/{id}/comments', [PostController::class, 'addComment'])->middleware('auth:sanctum');
});
