<?php


namespace ppil\view;


use ppil\util\AppContainer;

class UserView
{

    public static function creerUnCompte(): string
    {
        $template = file_get_contents('./html/creerCompte.html');

        $app = AppContainer::getInstance();

        return ViewRendering::render($template, 'Créer un compte');
    }

    public static function motDePasseOublie(): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/motDePasseOublie.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('password-forgotten_post');
        $template = str_replace('${post_url}', $urlPost, $template);

        return ViewRendering::render($template, 'Mot de passe oublié');
    }

    public static function seConnecter(): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/seConnecter.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('sin-in_post');
        $template = str_replace('${post_url}', $urlPost, $template);

        $urlForgotten = $app->getRouteCollector()->getRouteParser()->urlFor('password-forgotten');
        $template = str_replace('${password_forgotten}', $urlForgotten, $template);

        return ViewRendering::render($template, 'Connexion');
    }
}