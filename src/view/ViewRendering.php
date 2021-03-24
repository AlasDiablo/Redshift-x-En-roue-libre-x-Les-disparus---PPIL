<?php

namespace ppil\view;

use ppil\util\AppContainer;

class ViewRendering
{

    private static function getNavBar() {
        $nav = "<ul>";
        $connected = <<<html
        <li><a href="#">Trajet public</a></li>
        <li><a href="#">Trajet privé</a></li>
html;
        $notConnected = <<<html
        <li><a href="\${sin_in}">Me connecté</a></li>
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
    public static function render($body, $title): string
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
        $template = str_replace('${nav_bar}', self::getNavBar(), $template);

        // Se conencter Nav Bar
        $urlSinIn = $app->getRouteCollector()->getRouteParser()->urlFor('sin-in');
        $template = str_replace('${sin_in}', $urlSinIn, $template);

        // Site content

        $template = str_replace('${body}', $body, $template);

        return $template;
    }
}