<?php

namespace Ebski\ImageCompressor\Compressors;

/**
 * Class WebpCompressor
 *
 * @package Ebski\ImageCompressor\Compressors
 */
class WebpCompressor
{
    /**
     * @var array
     */
    private $storedImages;

    /**
     * @var bool
     */
    private $resize = false;

    /**
     * @var int|null
     */
    private $width;

    /**
     * @var int|null
     */
    private $height;

    public function __construct()
    {
        $this->storedImages = [];
    }

    public function __destruct()
    {
        foreach ($this->storedImages as $storedImage) {
            unlink($storedImage);
        }
    }

    /**
     * Compressor should resize all images to the following size
     *
     * @param int $width
     * @param int $height
     */
    public function resizeTo(int $width, int $height): void
    {
        $this->resize = true;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @param string $imagePath
     * @param int $quality
     * @return string
     */
    public function compressImage(string $imagePath, int $quality): string
    {
        $storagePath = sprintf('%s/CompressedImages/%s.webp', __DIR__, uniqid());
        $path = $storagePath;
        $resize = !$this->resize ? '' : sprintf(' -resize %u %u', $this->width, $this->height);
        $cmd = sprintf('cwebp -q %s%s %s -o %s', $quality, $resize, $imagePath, $storagePath);
        exec($cmd, $storagePath, $exitCode);
        $this->storedImages[] = $path;
        return $path;
    }
}