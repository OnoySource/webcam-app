<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get("/", function () {
  return view("index");
});
Route::post("/save-image", [ImageController::class, "saveImage"]);

Route::get("/redirect/to/admin", [ImageController::class, "show"]);
