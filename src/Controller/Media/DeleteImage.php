<?php


namespace App\Controller\Media;


use App\Service\Media\DeleteImageService;
use App\Service\Query\QueryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteImage
{
    private DeleteImageService $deleteImageService;

    public function __construct(DeleteImageService $deleteImageService)
    {
        $this->deleteImageService = $deleteImageService;
    }

    public function __invoke(Request $request, string $id): Response
    {
        $hash = QueryService::getParam($request, 'hash', false);
        $token = QueryService::getParam($request, 'token');

        return $this->deleteImageService->delete($id, $token, $hash);
    }
}