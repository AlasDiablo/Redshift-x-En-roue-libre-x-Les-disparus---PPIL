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

    public static function renderList($groups)
    {
        $out = '';
        foreach ($groups as $group) {
            $template = file_get_contents('./html/sub-element/case-group.html');
            $template = str_replace('${title}', $group->nom, $template);
            $template = str_replace('${url}', '#', $template);
            $out .= $template;
        }

        $templateView = file_get_contents('./html/list-group.html');
        $templateView = str_replace('${list_group}', $out, $templateView);

        return ViewRendering::render($templateView, 'Mes Groupes');
    }
}