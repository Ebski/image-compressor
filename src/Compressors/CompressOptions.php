<?php

namespace Ebski\ImageCompressor\Compressors;

/**
 * Class CompressOptions
 *
 * @package Ebski\ImageCompressor\Compressors
 */
class CompressOptions
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $quality;

    /**
     * @var int
     */
    private $squareResizeAndCropSize;

    /**
     * @param string $path
     * @param int $quality
     * @param int $squareResizeAndCropSize
     */
    public function __construct(
        string $path,
        int $quality,
        int $squareResizeAndCropSize
    ) {
        $this->path = $path;
        $this->quality = $quality;
        $this->squareResizeAndCropSize = $squareResizeAndCropSize;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getQuality(): int
    {
        return $this->quality;
    }

    /**
     * @return int
     */
    public function getSquareResizeAndCropSize(): int
    {
        return $this->squareResizeAndCropSize;
    }
}