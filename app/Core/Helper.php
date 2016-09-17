<?php

namespace CustomMVC\Core;

class Helper
{
    /**
     * @param string $url
     * @return string
     * limpia la URL de caracteres extraños
     */
    public static function cleanUrl($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }
}
