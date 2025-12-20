<?php

namespace core;

class ImageProcessor
{
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
            $x = ($baseWidth - $stickerWidth) / 2;
        }
        if ($y === null) {
            $y = ($baseHeight - $stickerHeight) / 2;
        }

        // Copy sticker onto base image
        imagecopy($baseImage, $sticker, $x, $y, 0, 0, $stickerWidth, $stickerHeight);

        imagedestroy($sticker);

        return $baseImage;
    }

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

    public static function resizeImage($image, $width, $height)
    {
        $newImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        return $newImage;
    }

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
