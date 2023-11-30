<?php

/*
|--------------------------------------------------------------------------
| Application Environment
|--------------------------------------------------------------------------
*/

\Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__, 2))->load();

/*
|--------------------------------------------------------------------------
| Application URL
|--------------------------------------------------------------------------
*/

define('URL_BASE', 'https://www.example.com');
define('URL_TEST', 'https://php-video-list.test');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/Helpers/Helper.php';
require_once __DIR__ . '/Helpers/Session.php';

/*
|--------------------------------------------------------------------------
| Minify
|--------------------------------------------------------------------------
*/

if (is_localhost()) {
    require_once __DIR__ . '/Minify/Web.php';
}

/*
|--------------------------------------------------------------------------
| Application Key
|--------------------------------------------------------------------------
*/

define('SITE_KEY', base64_decode($_ENV['APP_KEY']));

/*
|--------------------------------------------------------------------------
| Application Name
|--------------------------------------------------------------------------
*/

define('SITE_NAME', $_ENV['APP_NAME']);

/*
|--------------------------------------------------------------------------
| Application Timezone
|--------------------------------------------------------------------------
*/

define('SITE_TIMEZONE', 'America/Sao_Paulo');

/*
|--------------------------------------------------------------------------
| Application Locale Configuration
|--------------------------------------------------------------------------
|
| Idiomas disponíveis: [ pt_BR ]
|
*/

define('SITE_LOCALE', 'pt_BR');

/*
|--------------------------------------------------------------------------
| SEO
|--------------------------------------------------------------------------
*/

define('SITE_TITLE', ' • ' . SITE_NAME);
define('SITE_DESC', '');
define('SITE_DOMAIN', url_replace());

/*
|--------------------------------------------------------------------------
| Sleek Connection
|--------------------------------------------------------------------------
*/

define('SLEEK_DATA_CONFIG', [
    'auto_cache' => false,
    'cache_lifetime' => null,
    'timeout' => false,
    'primary_key' => '_id',
    'search' => [
        'min_length' => 3,
        'mode' => 'or',
        'score_key' => 'scoreKey',
        'algorithm' => \SleekDB\Query::SEARCH_ALGORITHM['hits']
    ],
    'folder_permissions' => 0777
]);

/*
|--------------------------------------------------------------------------
| Directorys
|--------------------------------------------------------------------------
*/

define('DIRECTORY_SLEEK', dirname(__DIR__, 2) . '/database/');

/*
|--------------------------------------------------------------------------
| Language
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/Language/' . SITE_LOCALE . '.php';