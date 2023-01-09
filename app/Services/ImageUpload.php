<?php

namespace App\Services;

class ImageUpload
{

    public static function upload($request, $fileName, $publicPath)
    {

        $image = $request->file($fileName);
        $reImage = time() . '.' . $image->getClientOriginalExtension();
        $dest = public_path($publicPath);
        $image->move($dest, $reImage);

        return $reImage;
    }

}