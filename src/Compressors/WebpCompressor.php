<?php

namespace Ebski\ImageCompressor\Compressors;

/**
 * Class WebpCompressor
 *
 * @package Ebski\ImageCompressor\Compressors
 */
class WebpCompressor
{
    private const TEMP_PATH_RESIZE = __DIR__ . '/CompressedImages/temp_resize.webp';
    private const TEMP_PATH_CROP = __DIR__ . '/CompressedImages/temp_crop.webp';

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

    /**
     * @param CompressOptions $options
     * @return string
     */
    public function resizeAndCropToSquare(CompressOptions $options): string
    {
        $resizeCommand = $this->getResizeCommand($options);
        exec($resizeCommand, $storagePath, $exitCode);
        $cropCommand = $this->getCropCommand($options);
        exec($cropCommand, $storagePath, $exitCode);
        $path = $this->compressImage($options->getPath(), $options->getQuality());
        unlink(self::TEMP_PATH_RESIZE);
        unlink(self::TEMP_PATH_CROP);
        return $path;
    }

    /**
     * @param CompressOptions $options
     * @return string
     */
    private function getResizeCommand(CompressOptions $options): string
    {
        $path = self::TEMP_PATH_RESIZE;
        $imageInfo = $this->getImageInfo($options->getPath());
        if ($imageInfo->getWidth() > $imageInfo->getHeight()) {
            $string = sprintf('0 %u', $options->getSquareResizeAndCropSize());
        } else {
            $string = sprintf('%u 0', $options->getSquareResizeAndCropSize());
        }
        return sprintf('cwebp -q 100 -resize %s %s -o %s', $string, $options->getPath(), $path);
    }

    /**
     * @param CompressOptions $options
     * @return string
     */
    private function getCropCommand(CompressOptions $options): string
    {
        $path = self::TEMP_PATH_CROP;
        $imageInfo = $this->getImageInfo($options->getPath());
        $halfWantedSize = intval($options->getSquareResizeAndCropSize() / 2);
        if ($imageInfo->getWidth() > $imageInfo->getHeight()) {
            $widthStart = intval($imageInfo->getWidth() / 2) - $halfWantedSize;
            $string = sprintf('%u 0', $widthStart);
        } else {
            $heightStart = intval($imageInfo->getHeight() / 2) - $halfWantedSize;
            $string = sprintf('%u 0', $heightStart);
        }

        return sprintf(
            'cwebp -q 100 -crop %s %s %s %s -o %s',
            $string,
            $options->getSquareResizeAndCropSize(),
            $options->getSquareResizeAndCropSize(),
            self::TEMP_PATH_RESIZE,
            $path
        );
    }

    /**
     * @param string $path
     * @return ImageInfo
     */
    private function getImageInfo(string $path): ImageInfo
    {
        $size = getimagesize($path);
        return new ImageInfo(
            $size[0],
            $size[1]
        );
    }
}