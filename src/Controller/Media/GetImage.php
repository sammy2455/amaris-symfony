<?php


namespace App\Controller\Media;


use App\Service\Media\GetImageService;
use App\Service\Query\QueryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetImage
{
    private GetImageService $getImageService;

    public function __construct(GetImageService $getImageService)
    {
        $this->getImageService = $getImageService;
    }

    public function __invoke(Request $request, string $id): Response
    {
        $hash = QueryService::getParam($request, 'hash', false);
        $format = QueryService::getParam($request, 'format', false);
        $quality = QueryService::getParam($request, 'size', false);
        $proportion = QueryService::getParam($request, 'proportion', false);

        $params = [
            "format" => $format,
            "quality" => $quality,
            "proportion" => $proportion,
        ];

        return $this->getImageService->get($id, $params, $hash);
    }
}