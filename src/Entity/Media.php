<?php


namespace App\Entity;


use Symfony\Component\Uid\Uuid;

class Media
{
    private string $id;
    private string $type;
    private string $format;
    private string $ext;
    private string $size;
    private ?string $width;
    private ?string $height;
    private ?string $hash;
    private string $token;
    private \DateTime $created_at;

    public function __construct(
        string $type,
        string $format,
        string $ext,
        string $size,
        ?string $width,
        ?string $height,
        ?string $hash
    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->type = $type;
        $this->format = $format;
        $this->ext = $ext;
        $this->size = $size;
        $this->width = $width;
        $this->height = $height;
        $this->hash = $hash;
        $this->token = sha1(uniqid());
        $this->created_at = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getExt(): string
    {
        return $this->ext;
    }

    /**
     * @param string $ext
     */
    public function setExt(string $ext): void
    {
        $this->ext = $ext;
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @param string $size
     */
    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    /**
     * @return string|null
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }

    /**
     * @param string|null $width
     */
    public function setWidth(?string $width): void
    {
        $this->width = $width;
    }

    /**
     * @return string|null
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * @param string|null $height
     */
    public function setHeight(?string $height): void
    {
        $this->height = $height;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @param string|null $hash
     */
    public function setHash(?string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }


}