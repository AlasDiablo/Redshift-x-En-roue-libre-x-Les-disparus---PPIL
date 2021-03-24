<?php


namespace ppil\view;


use ppil\util\AppContainer;

class IndexView
{
    public static function render(): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/index.html');

        $urlSinIn = $app->getRouteCollector()->getRouteParser()->urlFor('sign-in');
        $template = str_replace('${sign_in}', $urlSinIn, $template);

        $urlSinUp = $app->getRouteCollector()->getRouteParser()->urlFor('sign-up');
        $template = str_replace('${sign_up}', $urlSinUp, $template);

        return ViewRendering::render($template, '');
    }
}