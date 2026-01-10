<?php

namespace core;

/**
 * File class
 * 
 * This class is used to handle file operations.
 * Wraps low-level filesystem stuff so we don't have to deal with it directly.
 */
class File
{
    /**
     * Upload a file
     * 
     * The main goal of this method is to securely upload a file to the server.
     * For example, here we can upload avatar images and images from the gallery.
     * 
     * Some checks done here:
     * - Check if the file is allowed
     * - Check if the file is too big
     * - Check if the file is uploaded sucessfully
     * 
     * @param array $file
     * @param string $path
     * @return string
     */
    public function upload($file, $path)
    {
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        // We grab it by takinng the content after the last dot.
        $fileExt = explode('.', $fileName);
        // We convert it to lowercase.
        $fileActualExt = strtolower(end($fileExt));

        $allowed = ['jpg', 'jpeg', 'png'];
        $allowedMimeTypes = [IMAGETYPE_JPEG, IMAGETYPE_JPEG, IMAGETYPE_PNG];
        $mimeType = exif_imagetype($fileTmpName);
        // We use built-in function to get the mime type of the file.
        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new \Exception('You cannot upload files of this type!');
        }

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 50000000) { // 50MB limit
                    $fileNameNew = uniqid('', true) . '.' . $fileActualExt;
                    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }
                    $fileDestination = $targetDir . '/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    return $path . '/' . $fileNameNew;
                } else {
                    throw new \Exception('Your file is too big!');
                }
            } else {
                throw new \Exception('There was an error uploading your file!');
            }
        } else {
            throw new \Exception('You cannot upload files of this type!');
        }
    }

    /**
     * Remove a file
     * 
     * Deletes a file from the disk. 
     * Throws an exception if the file doesn't exist, so check first!
     * 
     * @param string $file
     * @return bool
     */
    public function remove($file)
    {
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . '/' . $file;
        if (file_exists($fileDestination)) {
            return unlink($fileDestination);
        } else {
            throw new \Exception('The file could not be found on the specified location');
        }
    }

    /**
     * Remove a file if it exists
     * 
     * Safer version of remove().
     * Checks if file exists before trying to delete it.
     * Returns true if deleted, false if not found.
     * 
     * @param string $file
     * @return bool
     */
    public function removeIfExists($file)
    {
        if ($this->exists($file)) {
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . '/' . $file;
            return unlink($fileDestination);
        } else {
            return false;
        }
    }

    /**
     * Check if a file exists
     * 
     * Simple wrapper around file_exists.
     * Helpful because it handles the document root path logic for us.
     * 
     * @param string $file
     * @return bool
     */
    public function exists($file)
    {
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . '/' . $file;
        return file_exists($fileDestination);
    }
}
