<?php


namespace ppil\controller;


use ppil\models\Notification;
use ppil\models\Trajet;
use ppil\models\Utilisateur;
use ppil\util\AppContainer;

class NotificationController
{
    private static function today()
    {
        setlocale(LC_TIME, "fr_FR");
        // March 10, 2001, 17:16
        return strftime("%B %e, %Y, %H:%M");
    }

    private static function participationDismiss($from, $for, $rideId, $text) {
        $fromUser = Utilisateur::where('email', '=', $from)->first();
        $today = self::today();
        $fromName = $fromUser->nom . ' ' . $fromUser->prenom;
        $ride = Trajet::where('id_trajet', '=', $rideId)->first();
        $rideFrom = $ride->ville_depart;
        $rideTo = $ride->ville_arrivee;
        $rideUrl = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('ride', array('id' => $rideId));
        $content = <<<text
$today<br>
$fromName, $text $rideFrom à $rideTo <a href="$rideUrl">(voir mon trajet)</a>.
text;
        $notif = new Notification();

        $notif->utilisateur = $for;
        $notif->emeteur = $from;
        $notif->message = $content;
        $id = Trajet::max('id_trajet');
        if (isset($id)) $id++;
        else $id = 0;
        $notif->id_notif = $id;
        $notif->save();
    }

    public static function sendMyParticipationTo($from, $for, $rideId)
    {
        self::participationDismiss($from, $for, $rideId, 'A rejoin votre trajet allent de');
    }

    public static function sendMyDismissTo($from, $for, $rideId)
    {
        self::participationDismiss($from, $for, $rideId, 'A annulé ça participation au trajet de');
    }
}