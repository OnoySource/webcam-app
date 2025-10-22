public function saveImage(Request $request)
{
    $type = $request->input('type', 'auto');

    // Hitung jumlah file sebelumnya
    $count = \Storage::files('public/images');
    $index = count($count) + 1;

    // Generate nama file => Ke-1_abcdefgh.png
    $random = Str::random(8);
    $filename = "Ke-{$index}_{$random}.png";

    if ($type === 'manual') {
        $image = $request->image;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);
        \Storage::put("public/images/{$filename}", $imageData);
    } else {
        $image = $request->file('image');
        $image->storeAs('public/images', $filename);
    }

    return response()->json(['success' => true, 'filename' => $filename]);
}