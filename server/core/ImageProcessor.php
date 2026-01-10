<?php

namespace core;

/**
 * ImageProcessor class
 * 
 * Handles all the heavy lifting for image manipulation.
 * We use GD library because it's standard and available everywhere.
 * Basically: load, edit, save.
 */
class ImageProcessor
{
    /**
     * Converts a base64 string to a GD image resource.
     * 
     * Front-end sends us base64 strings (data uris), so we need to clean them up and decode them.
     * If it fails, we throw exceptions because silent failurs are bad.
     * 
     * @param string $base64Data
     * @return resource
     */
    public static function base64ToImage($base64Data)
    {
        // Remove data URI scheme if present
        if (strpos($base64Data, 'data:image') === 0) {
            $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
        }

        $imageData = base64_decode($base64Data);
        if ($imageData === false) {
            throw new \Exception('Invalid Base64 image data');
        }

        $image = imagecreatefromstring($imageData);
        if ($image === false) {
            throw new \Exception('Failed to create image from data');
        }

        return $image;
    }

    /**
     * Merges a sticker onto a base image.
     * 
     * This is the core feature. We take a webcam shot and slap a cat sticker on it.
     * We handdle alpha blending to make sure transparency works correctly.
     * Also scales the sticker relative to the base image so it doesn't look tiny or huge.
     * 
     * @param resource $baseImage
     * @param string $stickerPath
     * @param int|null $x
     * @param int|null $y
     * @return resource
     */
    public static function mergeImages($baseImage, $stickerPath, $x = null, $y = null)
    {
        if (!file_exists($stickerPath)) {
            throw new \Exception('Sticker file not found: ' . $stickerPath);
        }

        $sticker = imagecreatefrompng($stickerPath);
        if ($sticker === false) {
            throw new \Exception('Failed to load sticker image');
        }

        // Enable alpha blending for transparency
        imagealphablending($baseImage, true);
        imagesavealpha($baseImage, true);

        $stickerWidth = imagesx($sticker);
        $stickerHeight = imagesy($sticker);
        $baseWidth = imagesx($baseImage);
        $baseHeight = imagesy($baseImage);

        // Scale sticker to 20% of the base image height
        $targetHeight = (int) ($baseHeight * 0.2);
        $scale = $targetHeight / $stickerHeight;
        $newWidth = (int) ($stickerWidth * $scale);
        $newHeight = $targetHeight;

        // Create scaled sticker
        $scaledSticker = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($scaledSticker, false);
        imagesavealpha($scaledSticker, true);
        $transparent = imagecolorallocatealpha($scaledSticker, 0, 0, 0, 127);
        imagefill($scaledSticker, 0, 0, $transparent);
        imagealphablending($scaledSticker, true);

        // Resample the sticker for high quality scaling
        imagecopyresampled($scaledSticker, $sticker, 0, 0, 0, 0, $newWidth, $newHeight, $stickerWidth, $stickerHeight);

        imagedestroy($sticker);
        $sticker = $scaledSticker;
        $stickerWidth = $newWidth;
        $stickerHeight = $newHeight;

        // Center sticker if position not specified
        if ($x === null) {
            $x = intval(($baseWidth - $stickerWidth) / 2);
        }
        if ($y === null) {
            $y = intval(($baseHeight - $stickerHeight) / 2);
        }

        // Copy sticker onto base image
        imagecopy($baseImage, $sticker, $x, $y, 0, 0, $stickerWidth, $stickerHeight);

        imagedestroy($sticker);

        return $baseImage;
    }

    /**
     * Saves the image resource to a file.
     * 
     * Supports PNG and JPEG.
     * Also creates the directory if it doesn't exist, because we're nice like that.
     * 
     * @param resource $image
     * @param string $path
     * @param string $format
     * @param int $quality
     * @return bool
     */
    public static function saveImage($image, $path, $format = 'png', $quality = 90)
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $format = strtolower($format);

        switch ($format) {
            case 'png':
                return imagepng($image, $path);
            case 'jpg':
            case 'jpeg':
                return imagejpeg($image, $path, $quality);
            default:
                throw new \Exception('Unsupported image format: ' . $format);
        }
    }

    /**
     * Resizes an image.
     * 
     * Uses resampling for better qualty.
     * Useful for thumbnails or normalizing sizes.
     * 
     * @param resource $image
     * @param int $width
     * @param int $height
     * @return resource
     */
    public static function resizeImage($image, $width, $height)
    {
        $newImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        return $newImage;
    }

    /**
     * Lists all available stickers.
     * 
     * Scans the stickers directory and returns a list so the UI can render the gallary.
     * 
     * @param string $stickersDir
     * @return array
     */
    public static function getAvailableStickers($stickersDir)
    {
        if (!is_dir($stickersDir)) {
            return [];
        }

        $stickers = [];
        $files = scandir($stickersDir);

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'png') {
                $stickers[] = [
                    'id' => pathinfo($file, PATHINFO_FILENAME),
                    'filename' => $file,
                    'path' => $stickersDir . '/' . $file
                ];
            }
        }

        return $stickers;
    }

    /**
     * Gets the safe path for a sticker ID.
     * 
     * Prevents directory traversal attacks by sanitizing the ID.
     * We don't want people loading /etc/passwd as a sticker.
     * 
     * @param string $stickerId
     * @param string $stickersDir
     * @return string
     */
    public static function getStickerPath($stickerId, $stickersDir)
    {
        // Sanitize sticker ID to prevent path traversal
        $stickerId = basename($stickerId);
        $stickerPath = $stickersDir . '/' . $stickerId . '.png';

        if (!file_exists($stickerPath)) {
            throw new \Exception('Invalid sticker ID');
        }

        return $stickerPath;
    }
}
