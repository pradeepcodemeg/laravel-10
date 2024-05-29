<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CommonHelper
{
    public static function fileUpload($file, $dir)
    {
        try {
            if (!empty($file)) {
                $fileName = time() . Str::random(4) . "." . $file->getClientOriginalExtension();
                $fullPath = public_path('uploads/' . $dir . '/' . $fileName);

                // Resize image to fit within 800x800 pixels and encode as JPG with 75% quality
                // $modifiedImage = Image::make($file)->resize(800, 800, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->encode('jpg', 75);

                // Compress image by encoding it as JPG with 75% quality
                $modifiedImage = Image::make($file)->encode('jpg', 75);

                // Ensure the directory exists, if not, create it
                if (!file_exists(public_path('uploads/' . $dir))) {
                    mkdir(public_path('uploads/' . $dir), 0777, true);
                }

                // Save the resized and compressed image
                $modifiedImage->save($fullPath);

                return $fileName;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function deleteImage($fileUrl)
    {
        // do not remove user's default image
        $noUserImage    =   config('constants.NO_USER_IMG');
        $defaultImg     =   config('constants.DEFAULT_IMAGE');

        if ($fileUrl == asset($noUserImage)) {
            return false;
        }

        if ($fileUrl == asset($defaultImg)) {
            return false;
        }

        $appUrl = config('app.url');

        $relativePath = str_replace($appUrl, '', $fileUrl);

        $localPath = public_path($relativePath);

        if (file_exists($localPath)) {
            unlink($localPath);
            return true;
        } else {
            return false;
        }
    }
}
