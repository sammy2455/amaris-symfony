<?php


namespace App\Exception\Media;


use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class TypeOfPrivacyNotAllowedException extends ConflictHttpException
{
    private const MESSAGE = "Type of privacy not allowed; Only 'public' or 'private' allowed";

    public static function fromPrivacy(): self
    {
        throw new self(self::MESSAGE);
    }
}