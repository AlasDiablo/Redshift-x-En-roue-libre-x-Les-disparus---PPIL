<?php

namespace ppil\view;

use ppil\util\AppContainer;

class ViewRendering
{

    private static function getNavBar() {
        return '';
    }

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

        // Site content

        $template = str_replace('${body}', $body, $template);

        return $template;
    }
}