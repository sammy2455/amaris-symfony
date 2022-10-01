<?php


namespace App\Exception\Media;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileNotFoundException extends NotFoundHttpException
{
    private const MESSAGE = "File with id '%s' not found";

    public static function fromId(string $id): self
    {
        throw new self(\sprintf(self::MESSAGE, $id));
    }
}