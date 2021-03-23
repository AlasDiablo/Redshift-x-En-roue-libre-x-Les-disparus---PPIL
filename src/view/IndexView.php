<?php


namespace ppil\view;


use ppil\util\AppContainer;

class IndexView
{
    public static function render(): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/index.html');

        $urlSinIn = $app->getRouteCollector()->getRouteParser()->urlFor('sin-in');
        $template = str_replace('${sin_in}', $urlSinIn, $template);

        $urlSinUp = $app->getRouteCollector()->getRouteParser()->urlFor('sin-up');
        $template = str_replace('${sin_up}', $urlSinUp, $template);

        return ViewRendering::render($template, '');
    }
}