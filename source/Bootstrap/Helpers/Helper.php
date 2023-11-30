<?php

/**
 * @return bool
 */
function is_localhost(): bool
{
    $whitelist = ['127.0.0.1', '::1'];
    if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
        return true;
    }

    return false;
}

/**
 * @return string
 */
function url(): string
{
    if (is_localhost()) {
        return URL_TEST;
    }

    return URL_BASE;
}

/**
 * @param string|null $url
 * 
 * @return string
 */
function url_replace(?string $url = null): string
{
    return str_replace('https://www.', '', $url ?? url());
}

/**
 * @param string $path
 * 
 * @return string
 */
function asset(string $path): string
{
    return '/' . ($path[0] == '/' ? mb_substr($path, 1) : $path);
}

/**
 * @param string $param
 * @param array $values
 * 
 * @return string
 */
function jsonResponse(string $param, array $values): string
{
    return json_encode([$param => $values]);
}
