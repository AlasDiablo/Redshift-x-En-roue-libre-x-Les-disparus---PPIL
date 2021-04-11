<?php


namespace ppil\view;


use ppil\util\AppContainer;

class GroupView
{
    public static function supprimerAmiGroupe(): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/suppAmisGroupe.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('delete-friend_post');
        $template = str_replace('${post_url}', $urlPost, $template);

        return ViewRendering::render($template, 'Supprimer un ami');
    }

    public static function ajouterAmiGroupe(): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/ajoutAmisGroupe.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('add-friend_post');
        $template = str_replace('${post_url}', $urlPost, $template);

        return ViewRendering::render($template, 'Ajouter un ami');
    }
}