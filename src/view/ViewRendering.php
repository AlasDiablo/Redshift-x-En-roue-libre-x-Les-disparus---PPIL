<?php

namespace ppil\view;

use ppil\controller\NotificationController;
use ppil\models\Utilisateur;
use ppil\util\AppContainer;

class ViewRendering
{

    private static function getNavBar($app)
    {
        $out = '<ul>';
        $urlRoot = $app->getRouteCollector()->getRouteParser()->urlFor('root');
        if (isset($_SESSION['mail'])) {
            $urlLogout = $app->getRouteCollector()->getRouteParser()->urlFor('logout');
            $urlProfile = $app->getRouteCollector()->getRouteParser()->urlFor('edit-profile');
            $urlPublicRide = $app->getRouteCollector()->getRouteParser()->urlFor('public-ride');
            $urlRides = $app->getRouteCollector()->getRouteParser()->urlFor('myrides');
            $urlNotification = $app->getRouteCollector()->getRouteParser()->urlFor('notifications');
            $notificationCount = NotificationController::getUnreadNotificationCount();
            $notificationCountText = ($notificationCount > 0) ? ' (' . $notificationCount . ')' : '';
            $user = Utilisateur::where('email', '=', $_SESSION['mail'])->first();
            $url_img = isset($user->url_img) ? $user->url_img : '/uploads/default';
            $out .= <<<html
<li><a href="$urlRoot">ShareMyRide</a></li>
<li><a href="$urlPublicRide">Trajet public</a></li>
<li><a href="#">Trajet privé</a></li>
<li><a href="$urlRides">MyRides</a></li>
<li><a href="$urlNotification">Mes Notification$notificationCountText</a></li>
<li><a href="$urlLogout">Se déconnecter</a></li>
<li><a href="$urlProfile">
    <img src="$url_img" alt="My Avatar" width="64px" height="64px">
</a></li>
html;
        } else {
            $urlSignIn = $app->getRouteCollector()->getRouteParser()->urlFor('sign-in');
            $out .= <<<html
<li><a href="$urlRoot">ShareMyRide</a></li>
<li><a href="$urlSignIn">Me connecter</a></li>
html;
        }
        return $out . '</ul>';
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

    public static function renderError(string $erreur = 'Undefined error'): string
    {
        return ViewRendering::render('Erreur - ' . $erreur, 'Erreur');
    }
}
