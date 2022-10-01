<?php


namespace App\Service\Media;


use App\Entity\Media;
use App\Exception\Media\CouldNotUploadImageOnServerException;
use App\Exception\Media\TypeOfPrivacyNotAllowedException;
use App\Repository\MediaRepository;
use App\Service\File\FileUploader;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UploadService
{
    private MediaRepository $mediaRepository;

    private FileUploader $fileUploader;

    public function __construct(MediaRepository $mediaRepository, FileUploader $fileUploader)
    {
        $this->mediaRepository = $mediaRepository;
        $this->fileUploader = $fileUploader;
    }

    public function upload(UploadedFile $file, string $privacy): Response
    {
        $privacy = $this->checkPrivacy($privacy);

        $imageSize = getimagesize($file); // type: array
        $mime = explode("/", $file->getMimeType());
        $type = $mime[0];
        $format = $mime[1];
        $ext = $file->getClientOriginalExtension();
        $size = filesize($file);
        $width = ($imageSize)? $imageSize[0] : null;
        $height = ($imageSize)? $imageSize[1] : null;
        $hash = ($privacy === 'private')? sha1(uniqid()) : null;

        $media = new Media($type, $format, $ext, $size, $width, $height, $hash);

        $this->saveImageLog($media);
        $this->uploadImageToServer($media, $file);
        $this->optimizeImageOnServer($media);

        $url = (!$media->getHash())?
            "/media/{$media->getId()}?format={$media->getFormat()}" :
            "/media/{$media->getId()}?hash={$media->getHash()}&format={$media->getFormat()}";

        return new JsonResponse([
            "status" => "ok",
            "url" => $url,
            "token" => $media->getToken(),
        ], 201);
    }

    private function optimizeImageOnServer(Media $media): void
    {
        $location = "{$this->fileUploader->getTargetDirectory()}/{$media->getId()}.{$media->getExt()}";
        $optimizerChain = OptimizerChainFactory::create();
        $optimizerChain->optimize($location);
    }

    private function uploadImageToServer(Media $media, UploadedFile $file): void
    {
        try {
            $this->fileUploader->upload($file, "{$media->getId()}.{$media->getExt()}");
        } catch (\Exception $e) {
            if ($this->mediaRepository->findOneById($media->getId())) {
                $this->mediaRepository->remove($media);
            }
            throw CouldNotUploadImageOnServerException::from();
        }
    }

    private function saveImageLog(Media $media): void
    {
        try {
            $this->mediaRepository->save($media);
        } catch (\Exception $e) {
            throw CouldNotUploadImageOnServerException::from();
        }
    }

    private function checkPrivacy(string $privacy): string
    {
        if ($privacy === 'public' or $privacy === 'private') {
            return $privacy;
        } else {
            throw TypeOfPrivacyNotAllowedException::fromPrivacy();
        }
    }
}