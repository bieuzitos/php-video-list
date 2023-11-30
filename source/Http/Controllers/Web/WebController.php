<?php

namespace Source\Http\Controllers\Web;

use Source\Http\Controllers\Controller;

use Source\Models\Movie;
use Source\Models\Category;

/**
 * Class WebController
 * 
 * @package Source\Http\Controllers\Web
 */
class WebController extends Controller
{
    /**
     * @param object|string $router
     * 
     * @return void
     */
    public function __construct(object|string $router)
    {
        parent::__construct($router);
    }

    /**
     * @return void
     */
    public function index(): void
    {
        $optimizer = $this->seo->render(
            'Início' . SITE_TITLE,
            SITE_DESC,
            $this->router->route('web.home')
        );

        echo $this->view->render('home/index', [
            'seo' => $optimizer,

            'movies' => (new Movie())->all(),
            'categories' => (new Category())->all()
        ]);
    }
}
