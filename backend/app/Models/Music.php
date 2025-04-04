<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $table = "musics";

    protected $fillable = ["title", "youtube_id", "views", "thumbnail"];

    protected $casts = [
        "views" => "integer",
    ];
}
