<?php


namespace App\Exception\Media;


use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CouldNotUploadImageToServerException extends ConflictHttpException
{
    private const MESSAGE = "Could Not Upload Image To Server";

    public static function from()
    {
        throw new self(\sprintf(self::MESSAGE));
    }
}