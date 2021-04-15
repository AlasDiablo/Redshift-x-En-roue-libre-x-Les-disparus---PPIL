<?php


namespace ppil\util;


class ImageChecker
{
    public static function checkAvatar($fileName)
    {
        if (!empty($_FILES['avatar']['name']))
        {
            $targetDir = realpath('uploads/');
            $targetFilePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
            $imageSize = getimagesize($_FILES['avatar']['tmp_name']);
            $fileSize = filesize($_FILES['avatar']['tmp_name']);
            if ($imageSize !== false && $fileSize !== false)
            {
                list($width, $height) = $imageSize;
                if ($width <= 400 && $height <= 400 && $fileSize <= 20971520) {
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFilePath))
                        return $fileName;
                    else return null;
                } else return null;
            } else return null;
        } else return 'no_image';
    }
}