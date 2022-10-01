<?php


namespace App\Service\File;


use App\Exception\Media\CouldNotDeleteImageOnServerException;
use App\Exception\Media\CouldNotUploadImageToServerException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private string $targetDirectory;

    private Filesystem $filesystem;

    public function __construct(string $targetDirectory, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file, string $fileName): void
    {
        $location = "{$this->targetDirectory}/";

        if (!$this->filesystem->exists($location)) {
            try {
                $this->filesystem->mkdir($location, 0755);
            } catch (\Exception $e) {
                throw CouldNotUploadImageToServerException::from();
            }
        }

        try {
            $file->move($location, $fileName);
        } catch (\Exception $e) {
            throw CouldNotUploadImageToServerException::from();
        }
    }

    public function remove(File $file): void
    {
        $location = "{$file->getPath()}/{$file->getFilename()}";

        try {
            $this->filesystem->remove($location);
        } catch (\Exception $e) {
            throw CouldNotDeleteImageOnServerException::from();
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}