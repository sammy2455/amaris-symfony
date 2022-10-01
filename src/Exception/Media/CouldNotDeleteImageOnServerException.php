<?php


namespace App\Exception\Media;


use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CouldNotDeleteImageOnServerException extends ConflictHttpException
{
    private const MESSAGE = "Could Not Delete Image On Server";

    public static function from(): self
    {
        throw new self(\sprintf(self::MESSAGE));
    }
}