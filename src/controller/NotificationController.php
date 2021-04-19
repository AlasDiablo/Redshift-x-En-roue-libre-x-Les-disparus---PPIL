<?php


namespace ppil\controller;


use ppil\models\Groupe;
use ppil\models\Notification;
use ppil\models\Trajet;
use ppil\models\Utilisateur;
use ppil\util\AppContainer;
use ppil\view\NotificationView;
use ppil\view\ViewRendering;

class NotificationController
{
    private static function today()
    {
        setlocale(LC_TIME, "fr_FR");
        // March 10, 2001, 17:16
        return strftime("%B %e, %Y, %H:%M");
    }

    private static function sendNotification($from, $for, $text)
    {
        $fromUser = Utilisateur::where('email', '=', $from)->first();
        $today = self::today();
        $fromName = $fromUser->nom . ' ' . $fromUser->prenom;
        $content = <<<text
<div class="card mt-4">
    <div class="card-header">
        $today, par $fromName
    </div>
    <div class="card-body container">
        $text
    </div>
</div>
text;
        $notif = new Notification();
        $notif->utilisateur = $for;
        $notif->emeteur = $from;
        $notif->message = $content;
        $id = Notification::max('id_notif');
        if (isset($id)) $id++;
        else $id = 0;
        $notif->id_notif = $id;
        $notif->save();
    }


    private static function participationDismiss($from, $for, $rideId, $text)
    {
        $ride = Trajet::where('id_trajet', '=', $rideId)->first();
        $rideFrom = $ride->ville_depart;
        $rideTo = $ride->ville_arrivee;
        $rideUrl = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('ride', array('id' => $rideId));
        $content = <<<text
<div class="row m-2">
    <div class="col">$text $rideFrom à $rideTo <a href="$rideUrl">(voir mon trajet)</a>.</div>
</div>
text;
        self::sendNotification($from, $for, $content);
    }

    public static function sendMyParticipationTo($from, $for, $rideId)
    {
        self::participationDismiss($from, $for, $rideId, 'A rejoint votre trajet allant de');
    }

    public static function sendMyDismissTo($from, $for, $rideId)
    {
        self::participationDismiss($from, $for, $rideId, 'A annulé sa participation au trajet de');
    }

    public static function sendGroupInvitation($from, $for, $groupID)
    {
        $acceptUrl = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('group-invit-accept', array('id' => $groupID));
        $declineUrl = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('group-invit-decline', array('id' => $groupID));
        $name = Groupe::where('id_groupe', '=', $groupID)->first()->nom;
        $content = <<<text
<div class="row m-2">
    <div class="col">Vous avez été invité à rejoindre le groupe <b>$name</b>.</div>
</div>
<div class="row m-2">
    <button type="button" class="btn btn-outline-success col" onclick="location.replace('$acceptUrl')">Accpeté</button>
    <button class="btn btn-outline-danger col" type="button" onclick="location.replace('$declineUrl')">Refusais</button>
</div>
text;
        self::sendNotification($from, $for, $content);
    }

    public static function renderNotificationsList()
    {
        if (isset($_SESSION['mail'])) {
            $data = Notification::where('utilisateur', '=', $_SESSION['mail'])->orderBy('id_notif', 'desc')->get();
            Notification::where('utilisateur', '=', $_SESSION['mail'])->update(['vu' => 'O']);
            return NotificationView::renderNotificationsList($data);
        } else {
            return ViewRendering::renderError('Forbidden');
        }
    }

    public static function getUnreadNotificationCount()
    {
        return Notification::where('utilisateur', '=', $_SESSION['mail'])->where('vu', '=', 'N')->count();
    }
}