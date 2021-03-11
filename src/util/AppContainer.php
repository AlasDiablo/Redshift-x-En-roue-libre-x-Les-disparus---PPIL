<?php

namespace ppil\util;

use Slim\Factory\AppFactory;

class AppContainer
{
    private static $app = null;

    public static function getInstance()
    {
        if (null === self::$app) {
            self::$app = AppFactory::create();
        }
        return self::$app;
    }
}