<?php


namespace App\Exception\Media;


use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CouldNotUploadImageOnServerException extends ConflictHttpException
{
    private const MESSAGE = "Could Not Upload Image On Server";

    public static function from(): self
    {
        throw new self(self::MESSAGE);
    }
}