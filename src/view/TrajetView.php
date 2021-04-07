<?php


namespace ppil\view;


use ppil\util\AppContainer;
use ppil\controller\TrajetController;

class TrajetView
{

    public static function creerTrajet(): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/creerTrajet.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('create-trajet_post');
        $template = str_replace('${post_url}', $urlPost, $template);

        return ViewRendering::render($template, 'Créer un trajet');
    }

    public static function erreurPost(string $erreur = 'Undefined error')
    {
        return ViewRendering::render('Erreur - ' . $erreur, 'Erreur');
    }
}
