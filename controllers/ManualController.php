<?php

namespace Controllers;

use Exception;
use MVC\Router;

class ManualController
{
    public static function index(Router $router)
    {
        $router->render('manual/index', []);
    }
}
