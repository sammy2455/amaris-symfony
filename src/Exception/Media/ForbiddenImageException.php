<?php


namespace App\Exception\Media;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ForbiddenImageException extends AccessDeniedHttpException
{
    private const MESSAGE = "Access denied for image.";

    public static function fromId(): self
    {
        throw new self(self::MESSAGE);
    }
}