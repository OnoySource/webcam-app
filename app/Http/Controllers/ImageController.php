<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ImageManual;
use App\Models\ImageAuto;

class ImageController extends Controller
{public function saveImage(Request $request)
{
    $type = $request->input("type");

    if ($type == "auto" && $request->hasFile("image")) {
        // Hitung jumlah auto sebelumnya
        $count = ImageAuto::count() + 1;

        // bikin nama file
        $random = bin2hex(random_bytes(4)); // random 8 char
        $filename = "Ke-{$count}_{$random}.png";

        // save ke folder
        $image = $request->file("image");
        $path = $image->storeAs("images_auto", $filename, "public");

        // save ke database
        ImageAuto::create(["path" => $path]);

    } elseif ($type == "manual" && $request->has("image")) {
        // Hitung jumlah manual sebelumnya
        $count = ImageManual::count() + 1;

        // bikin nama file
        $random = bin2hex(random_bytes(4));
        $filename = "Ke-{$count}_{$random}.png";

        // decode base64
        $data = $request->input("image");
        list($type, $data) = explode(";", $data);
        list(, $data) = explode(",", $data);
        $data = base64_decode($data);

        // save ke folder
        $path = "images_manual/" . $filename;
        Storage::disk("public")->put($path, $data);

        // save ke database
        ImageManual::create(["image" => $path]);

    } else {
        return response()->json(["error" => "Invalid image or type"], 400);
    }

    return response()->json(["success" => true, "filename" => $filename]);
}
}    