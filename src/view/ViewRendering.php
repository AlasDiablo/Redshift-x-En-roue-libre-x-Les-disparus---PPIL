<?php

namespace ppil\view;

use ppil\models\Utilisateur;
use ppil\util\AppContainer;

class ViewRendering
{

    private static function getNavBar($app)
    {
        $nav = "<ul>";
        $urlSignIn = $app->getRouteCollector()->getRouteParser()->urlFor('sign-in');
        $urlLogout = $app->getRouteCollector()->getRouteParser()->urlFor('logout');
        $urlProfile = $app->getRouteCollector()->getRouteParser()->urlFor('edit-profile');
        $urlRoot = $app->getRouteCollector()->getRouteParser()->urlFor('root');
        $connected = <<<html
        <li><a href="$urlRoot">ShareMyRide</a></li>
        <li><a href="#">Trajet public</a></li>
        <li><a href="#">Trajet privé</a></li>
        <li><a href="$urlLogout">Se déconnecter</a></li>
        
html;
        if (isset($_SESSION['mail'])) {
            $user = Utilisateur::where('email', '=', $_SESSION['mail'])->first();
            $url_img = $user->url_img;
            $connected .= <<<html
<li><a href="$urlProfile">
    <img src="$url_img" alt="My Avatar" width="64px" height="64px">
</a></li>
html;
        } else {
            $connected .= <<<html
<li><a href="$urlProfile">
    <img src="/uploads/default" alt="My Avatar" width="64px" height="64px">
</a></li>
html;
        }


        $notConnected = <<<html
        <li><a href="$urlRoot">ShareMyRide</a></li>
        <li><a href="$urlSignIn">Me connecter</a></li>
html;
        $nav .= (isset($_SESSION['mail'])) ? $connected : $notConnected;
        $nav .= "</ul>";
        return $nav;
    }

    /**
     * @param $body string Contenue du site
     * @param $title string titre de la page (chaine vide pour le titre classic)
     * @return string Page du site formaté et pres a etre affiché
     */
    public static function render(string $body, string $title): string
    {
        $template = file_get_contents('./html/template.html');

        // Recuparation de l'app pour la creation de lien
        $app = AppContainer::getInstance();

        // Site title

        if ($title != "") {
            $template = str_replace('${title}', " - $title", $template);
        } else {
            $template = str_replace('${title}', "", $template);
        }

        // Web Site link
        $template = str_replace('${nav_bar}', self::getNavBar($app), $template);

        // Site content

        $template = str_replace('${body}', $body, $template);

        return $template;
    }
}
