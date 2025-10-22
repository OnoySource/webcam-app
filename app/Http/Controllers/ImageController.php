<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ImageManual;
use App\Models\ImageAuto;

class ImageController extends Controller
{
    public function saveImage(Request $request)
    {
        $type = $request->input("type");

        if ($type == "auto" && $request->hasFile("image")) {
            $image = $request->file("image");
            $path = $image->store("images_auto", "public");
            ImageAuto::create(["path" => $path]);
        } elseif ($type == "manual" && $request->has("image")) {
            $data = $request->input("image");
            list($type, $data) = explode(";", $data);
            list(, $data) = explode(",", $data);
            $data = base64_decode($data);
            $path = "images_manual/" . uniqid() . ".png";
            Storage::disk("public")->put($path, $data);
            ImageManual::create(["image" => $path]);
        } else {
            return response()->json(["error" => "Invalid image or type"], 400);
        }

        return response()->json(["success" => "Kesalahan dalam pengambilan gambar!"]);
    }

    public function show()
    {
        $imageAuto = ImageAuto::latest()->get();
        $imageManual = ImageManual::latest()->get();
        $countAuto = $imageAuto->count();
        $countManual = $imageManual->count();
        return view("admin.show", [
            "imagesAuto" => $imageAuto,
            "imagesManual" => $imageManual,
            "countAuto" => $countAuto,
            "countManual" => $countManual,
        ]);
    }
}