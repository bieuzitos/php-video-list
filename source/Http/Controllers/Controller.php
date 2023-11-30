<?php

namespace Source\Http\Controllers;

use Source\Support\Seo;

use CoffeeCode\Router\Router;
use League\Plates\Engine;
use League\Plates\Extension\Asset;

/**
 * Class Controller
 * 
 * @package Source\Http\Controllers
 */
class Controller
{
    /** @var Router */
    protected $router;

    /** @var View */
    protected $view;

    /** @var Seo */
    protected $seo;

    /**
     * @param object|string $router
     * @param string $pathToViews
     * @param string $fileExtension
     * 
     * @return void
     */
    public function __construct(object|string $router, string $pathToViews = '/web', string $fileExtension = 'php')
    {
        $this->router = $router;

        $this->view = (new Engine(dirname(__DIR__, 3) . '/resources' . $pathToViews . '/views', $fileExtension));
        $this->view->loadExtension(new Asset(dirname(__DIR__, 3) . '/public/', true));
        $this->view->addData(['router' => $this->router]);

        $this->seo = new Seo();
    }
}
