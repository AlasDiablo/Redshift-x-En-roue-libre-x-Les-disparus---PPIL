<?php


namespace ppil\view;


use ppil\util\AppContainer;

class UserView
{

    public static function creerUnCompte(): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/creerCompte.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('sign-up_post');
        $template = str_replace('${post_url}', $urlPost, $template);

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

    public static function motDePasseOublieForm($token): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/motDePasseOublieForm.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('password-forgotten-key_post', array('key' => $token));
        $template = str_replace('${post_url}', $urlPost, $template);

        return ViewRendering::render($template, 'Mot de passe oublié');
    }

    public static function seConnecter(): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/seConnecter.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('sign-in_post');
        $template = str_replace('${post_url}', $urlPost, $template);

        $urlForgotten = $app->getRouteCollector()->getRouteParser()->urlFor('password-forgotten');
        $template = str_replace('${password_forgotten}', $urlForgotten, $template);

        return ViewRendering::render($template, 'Connexion');
    }

    public static function modifierProfil($data): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/modifProfil.html');

        // Image
        if (isset($data->url_img)) $template = str_replace('${my_avatar}', $data->url_img, $template);
        else$template = str_replace('${my_avatar}', '/uploads/default', $template);

        // Set url
        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('edit-profile_post');
        $template = str_replace('${post_url}', $urlPost, $template);

        // Set data
        $template = str_replace('${name}', $data->nom, $template);
        $template = str_replace('${firstName}', $data->prenom, $template);
        $template = str_replace('${mail}', $data->email, $template);
        $template = str_replace('${tel}', '0' . $data->tel, $template);
        $template = str_replace($data->sexe == 'H' ? '${H}' : '${F}', 'checked', $template);
        $template = str_replace($data->a_voiture == 'O' ? '${yes}' : '${no}', 'checked', $template);
        $template = str_replace($data->sexe != 'H' ? '${H}' : '${F}', '', $template);
        $template = str_replace($data->a_voiture != 'O' ? '${yes}' : '${no}', '', $template);

        return ViewRendering::render($template, 'Mofifier mon profil');
    }

    public static function erreurPost(string $erreur = 'Undefined error')
    {
        return ViewRendering::render('Erreur - ' . $erreur, 'Erreur');
    }
}
