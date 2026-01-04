<?php

namespace core\helpers;

/**
 * HtmlHelper class
 * 
 * Helper for generating HTML tags.
 * Makes views cleaner by abstracting away the boring HTML syntax.
 */
class HtmlHelper
{
    private $baseUrl;

    public function __construct()
    {
        $baseUrl = getenv('BASE_URL');
        if (!$baseUrl) {
            $baseUrl = '';
        } else {
            $this->baseUrl = $baseUrl . substr($baseUrl, -1) == '/' ? '' : '/';
        }
    }

    /**
     * Generate a CSS link tag
     * 
     * Points to our public/css folder .
     * 
     * @param string $filename
     * @return string
     */
    public function css($filename)
    {
        return "<link rel='stylesheet' href='{$this->baseUrl}/css/{$filename}'>";
    }

    /**
     * Generate a JS script tag
     * 
     * Points to our public/js folder.
     * Supports adding extra atributes like defer or async.
     * 
     * @param string $filename
     * @param array $attributes
     * @return string
     */
    public function js($filename, $attributes = [])
    {
        return "<script src='{$this->baseUrl}/js/{$filename}' " . implode(' ', array_map(function ($key, $value) {
            return "$key='$value'";
        }, array_keys($attributes), $attributes)) . "></script>";
    }

    /**
     * Generate an IMG tag
     * 
     * Points to our public/images folder.
     * 
     * @param string $filename
     * @param string $alt
     * @param string $class
     * @return string
     */
    public function img($filename, $alt = '', $class = '')
    {
        return "<img src='{$this->baseUrl}/images/{$filename}' alt='{$alt}' class='{$class}'>";
    }

    /**
     * Get a URL relative to base path
     * 
     * Just apends the path to the base URL.
     * 
     * @param string $path
     * @return string
     */
    public function url($path)
    {
        return $this->baseUrl . $path;
    }
}
