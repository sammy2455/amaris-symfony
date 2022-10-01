<?php


namespace App\Exception\Media;


use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class FileFormatNotSupportedException extends UnsupportedMediaTypeHttpException
{
    private const MESSAGE = "Unsupported Media Type Or File Is Not An Image";

    public static function fromFormat(): self
    {
        throw new self(self::MESSAGE);
    }
}