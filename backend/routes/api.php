<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusicController;
use Illuminate\Support\Facades\Route;

Route::get("/musics", [MusicController::class, "index"]);

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);
Route::middleware("auth:sanctum")->post("/logout", [
    AuthController::class,
    "logout",
]);
