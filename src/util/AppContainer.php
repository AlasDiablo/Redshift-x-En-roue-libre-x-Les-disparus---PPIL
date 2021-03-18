<?php

namespace ppil\util;

use Slim\App;
use Slim\Factory\AppFactory;

class AppContainer
{
    private static $app = null;

    /**
     * @return App|null Instance de l'application slim
     */
    public static function getInstance()
    {
        if (null === self::$app) {
            self::$app = AppFactory::create();
        }
        return self::$app;
    }
}