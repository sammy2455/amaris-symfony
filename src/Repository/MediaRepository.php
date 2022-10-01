<?php


namespace App\Repository;


use App\Entity\Media;
use App\Exception\Media\FileNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class MediaRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return Media::class;
    }

    public function findOneByIdOrFail(string $id): Media
    {
        if (null === $media = $this->objectRepository->findOneBy(['id' => $id])) {
            throw FileNotFoundException::fromId($id);
        }

        return $media;
    }

    public function findOneById(string $id): ?Media
    {
        return $media = $this->objectRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param Media $media
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Media $media): void
    {
        $this->saveEntity($media);
    }

    /**
     * @param Media $media
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Media $media): void
    {
        $this->removeEntity($media);
    }
}