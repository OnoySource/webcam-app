<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageAuto extends Model
{
    use HasFactory;

    protected $table = 'image_autos';

    protected $fillable = ["path"];
}