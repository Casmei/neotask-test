<?php

use App\Http\Controllers\MusicController;
use Illuminate\Support\Facades\Route;

Route::get("/musics", [MusicController::class, "index"]);
