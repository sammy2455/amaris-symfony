<?php


namespace App\Controller\Image;


use App\Service\File\FileService;
use App\Service\Media\UploadService;
use App\Service\Query\QueryService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Upload
{
    private UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /*
     *  Method: [POST]
     *  Params: privacy => option: public, private; required
     *  Description: Endpoint to upload image to server
     * */
    public function __invoke(Request $request): Response
    {
        // Image can be fetched as binary or form-data
        // $imageFile = $request->getContent(); // Binary

        // $imageFile = FileService::getFile($request, 'image', true); // form-data
        // $image = file_get_contents($imageRequest); // type: string

        $imageRequest = FileService::getFile($request, 'image', true); // type: path
        $privacyRequest = QueryService::getParam($request, 'privacy');

        return $this->uploadService->upload($imageRequest, $privacyRequest);
    }
}