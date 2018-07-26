<?php
declare(strict_types=1);


namespace DealPerch\Retail\Client\DTO;

class Image implements \JsonSerializable
{
    /**
     * @var string
     */
    private $URL;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     *
     */
    private $width;

    /**
     * Image constructor.
     * @param string $URL
     * @param int $height
     * @param int $width
     */
    public function __construct(string $URL, int $height, int $width)
    {
        $this->URL = $URL;
        $this->height = $height;
        $this->width = $width;
    }

    public function jsonSerialize(): array
    {
        return [
            'URL' => $this->URL,
            'height' => $this->height,
            'width' => $this->width
        ];
    }

    /**
     * @return mixed
     */
    public function getURL(): string
    {
        return $this->URL;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }
}