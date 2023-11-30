<?php

use Source\Http\Middlewares\CsrfMiddleware as Csrf;

use CoffeeCode\Router\Router;

/*
|--------------------------------------------------------------------------
| Router Start
|--------------------------------------------------------------------------
*/

$router = new Router(url());

/*
|--------------------------------------------------------------------------
| Router Web
|--------------------------------------------------------------------------
*/

$router->namespace('Source\Http\Controllers\Web');

$router->group(null);
$router->get('/', 'WebController:index', 'web.home');

/*
|--------------------------------------------------------------------------
| Router Api
|--------------------------------------------------------------------------
*/

$router->namespace('Source\Http\Controllers\Api\v1');

$router->group('api/v1', [Csrf::class]);
$router->post('/movie/create', 'MovieController:create', 'movie.create');
$router->post('/movie/delete', 'MovieController:delete', 'movie.delete');

$router->post('/category/list', 'CategoryController:list', 'category.list');
$router->post('/category/create', 'CategoryController:create', 'category.create');
$router->post('/category/delete', 'CategoryController:delete', 'category.delete');

/*
|--------------------------------------------------------------------------
| Router End
|--------------------------------------------------------------------------
*/

$router->dispatch();

/*
|--------------------------------------------------------------------------
| Router Error
|--------------------------------------------------------------------------
*/

if ($router->error()) {
    http_response_code($router->error());

    echo json_encode([
        'status_type' => 'Bad Request',
        'status_message' => 'There was a problem with your request',
        'status' => false
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
