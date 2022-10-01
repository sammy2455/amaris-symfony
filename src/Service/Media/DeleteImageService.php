<?php


namespace App\Service\Media;


use App\Entity\Media;
use App\Exception\Media\CouldNotDeleteImageOnServerException;
use App\Exception\Media\ForbiddenImageException;
use App\Repository\MediaRepository;
use App\Service\File\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteImageService
{
    private MediaRepository $mediaRepository;

    private FileUploader $fileUploader;

    public function __construct(MediaRepository $mediaRepository, FileUploader $fileUploader)
    {
        $this->mediaRepository = $mediaRepository;
        $this->fileUploader = $fileUploader;
    }

    public function delete(string $id, string $token, ?string $hash): Response
    {
        /** @var Media $media */
        $media = $this->mediaRepository->findOneByIdOrFail($id);

        $this->checkHash($media->getHash(), $hash);
        $this->checkToken($media->getToken(), $token);

        $this->removeImageLog($media);
        $this->removeImageFromServer($media);

        return new JsonResponse(["status" => "ok"]);
    }

    private function removeImageFromServer(Media $media): void
    {
        try {
            $location = "{$this->fileUploader->getTargetDirectory()}/{$media->getId()}.{$media->getExt()}";
            $file = new File($location);
            $this->fileUploader->remove($file);
        } catch (\Exception $e) {
            $this->mediaRepository->save($media);
            throw CouldNotDeleteImageOnServerException::from();
        }
    }

    private function removeImageLog(Media $media): void
    {
        try {
            $this->mediaRepository->remove($media);
        } catch (\Exception $e) {
            throw CouldNotDeleteImageOnServerException::from();
        }
    }

    private function checkToken(string $tokenImage, string $tokenRequest): void
    {
        if ($tokenImage !== $tokenRequest) {
            throw ForbiddenImageException::fromId();
        }
    }

    private function checkHash(?string $hashImage, ?string $hashRequest): void
    {
        if (!empty($hashImage) and !empty($hashRequest)) {
            if ($hashImage !== $hashRequest) {
                throw ForbiddenImageException::fromId();
            }
        } else if (!empty($hashImage) and empty($hashRequest)) {
            throw ForbiddenImageException::fromId();
        }
    }
}