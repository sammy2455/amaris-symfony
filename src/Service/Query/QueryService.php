<?php


namespace App\Service\Query;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QueryService
{
    public static function getParam(Request $request, string $paramName, bool $isRequired = true)
    {
        $queryData = $request->query;

        if ($param = $queryData->get($paramName)) {
            return $param;
        }

        if ($isRequired) {
            throw new BadRequestHttpException(\sprintf("Missing '%s' param.", $paramName));
        }

        return null;
    }
}