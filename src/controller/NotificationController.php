<?php


namespace ppil\controller;


use ppil\models\Notification;
use ppil\models\Trajet;
use ppil\models\Utilisateur;
use ppil\util\AppContainer;

class NotificationController
{
    public static function sendMyParticipationTo($from, $for, $rideId) {
        $fromUser = Utilisateur::where('email', '=', $from)->first();
        setlocale(LC_TIME, "fr_FR");
        // March 10, 2001, 17:16
        $today = strftime("%B %e, %Y, %H:%M");
        $fromName = $fromUser->nom . ' ' . $fromUser->prenom;
        $ride = Trajet::where('id_trajet', '=', $rideId)->first();
        $rideFrom = $ride->ville_depart;
        $rideTo = $ride->ville_arrivee;
        $rideUrl = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('ride', array('id' => $rideId));
        $content = <<<text
$today<br>
$fromName, A rejoin votre trajet allent de $rideFrom à $rideTo <a href="$rideUrl">(voir mon trajet)</a>.
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
}