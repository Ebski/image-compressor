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
     * @param string $imagePath
     * @param int $quality
     * @return string
     */
    public function compressImage(string $imagePath, int $quality): string
    {
        $storagePath = sprintf('%s/CompressedImages/%s.webp', __DIR__, uniqid());
        $path = $storagePath;
        $cmd = sprintf('cwebp -q %s %s -o %s', $quality, $imagePath, $storagePath);
        exec($cmd, $storagePath, $exitCode);
        $this->storedImages[] = $path;
        return $path;
    }
}