<?php

namespace core\helpers;

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

    public function css($filename)
    {
        return "<link rel='stylesheet' href='{$this->baseUrl}/css/{$filename}'>";
    }

    public function js($filename)
    {
        return "<script src='{$this->baseUrl}/js/{$filename}'></script>";
    }

    public function img($filename, $alt = '', $class = '')
    {
        return "<img src='{$this->baseUrl}/images/{$filename}' alt='{$alt}' class='{$class}'>";
    }

    public function url($path)
    {
        return $this->baseUrl . $path;
    }
}
