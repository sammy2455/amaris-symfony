<?php


namespace App\Service\File;


use App\Exception\Media\FileFormatNotSupportedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileService
{
    public static function getFile(Request $request, string $fileName, bool $isImage = false, bool $isRequired = true): ?UploadedFile
    {
        $filesData = $request->files;

        /** @var $param UploadedFile */
        if (!empty($param = $filesData->get($fileName))) {

            if ($isImage) {
                if(!self::isImage($param)) {
                    throw FileFormatNotSupportedException::fromFormat();
                }
            }

            return $param;
        }

        if ($isRequired) {
            throw new BadRequestHttpException(\sprintf('Missing param %s', $fileName));
        }

        return null;
    }

    private static function isImage(UploadedFile $file): bool
    {
        $imagesTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP, IMAGETYPE_JPEG2000];
        $imagesTypesMime = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        //, 'image/svg', 'image/svg+xml'

        list($width, $height, $type, $attr) = @getimagesize($file);

        //$imageSize = @getimagesize($file);
        //if ($imageSize === false) return false;

        if (in_array($type, $imagesTypes) or in_array($file->getMimeType(), $imagesTypesMime)) {
            return true;
        }

        return false;
    }
}