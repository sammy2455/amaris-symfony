<?php


namespace App\Service\Media;


use App\Entity\Media;
use App\Exception\Media\ForbiddenImageException;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class GetImageService
{
    private MediaRepository $mediaRepository;

    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function get(string $id, array $params, ?string $hash): Response
    {
        /** @var Media $media */
        $media = $this->mediaRepository->findOneByIdOrFail($id);
        $this->checkHash($media->getHash(), $hash);

        list($format, $quality, $proportion) = $this->sanitizeParameter($params);

        if (!$this->checkFormat($format)) {
            return new Response();
        }

        $image = $this->getImage($media);
        $image = $this->prepareImage($image, $proportion, $format, $media, $quality);


        $response = new Response();
        $response->setContent($image);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', "image/{$format}");
        $response->headers->addCacheControlDirective('max-age', 604800);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    private function prepareImage($image, $proportion, $format, Media $media, $quality = 75): string
    {
        if ($format === $media->getFormat() and $quality === 75 and $proportion === 1) return $image;

        $source = imagecreatefromstring($image);
        list($width, $height) = getimagesizefromstring($image);

        $newWidth = $width/$proportion;
        $newHeight = $height/$proportion;

        $destination = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        if ($format === 'jpeg') {
            ob_start();
            imagewebp($destination, null, $quality);
            imagedestroy($destination);
            $output = ob_get_clean();
        } else if ($format === 'webp') {
            ob_start();
            imagejpeg($destination, null, $quality);
            imagedestroy($destination);
            $output = ob_get_clean();
        } else if ($format === 'gif') {
            ob_start();
            imagegif($destination, null);
            imagedestroy($destination);
            $output = ob_get_clean();
        } else if ($format === 'png') {
            ob_start();
            imagepng($destination, null, 7);
            imagedestroy($destination);
            $output = ob_get_clean();
        }

        return $output;
    }

    private function getImage(Media $media)
    {
        $location = "{$_ENV['PATH_TO_UPLOAD_FILE']}/{$media->getId()}.{$media->getExt()}";
        return file_get_contents($location);
    }

    private function sanitizeParameter($params): array
    {
        function checkTypeData($param, $type = 'integer')
        {
            if (settype($param, $type)) {
                return $param;
            } else {
                throw new ConflictHttpException(sprintf('Invalid parameter %s', $param));
            }
        }

        $format = checkTypeData($params['format'], 'string');
        $quality = ($params['quality'])? checkTypeData($params['quality']) : 75;
        $proportion = ($params['proportion'])? checkTypeData($params['proportion']) : 1;

        return [$format, $quality, $proportion];
    }

    private function checkFormat(string $format): bool
    {
        if (!isset($format) or empty($format) or !in_array($format, ['jpeg', 'webp', 'gif', 'png'])) {
            return false;
        }

        return true;
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