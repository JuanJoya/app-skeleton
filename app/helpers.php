<?php

if ( !function_exists('cleanUrl') ) {
    /**
     * Undocumented function
     *
     * @param string $url
     * @return string|false
     */
    function cleanUrl($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }
}
