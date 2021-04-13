<?php


namespace ppil\controller;


use PHPUnit\TextUI\XmlConfiguration\Group;
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
$today, par $fromName<br>
$text
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
$text $rideFrom à $rideTo <a href="$rideUrl">(voir mon trajet)</a>.
text;
        self::sendNotification($from, $for, $content);
    }

    public static function sendMyParticipationTo($from, $for, $rideId)
    {
        self::participationDismiss($from, $for, $rideId, 'A rejoin votre trajet allent de');
    }

    public static function sendMyDismissTo($from, $for, $rideId)
    {
        self::participationDismiss($from, $for, $rideId, 'A annulé ça participation au trajet de');
    }

    public static function sendGroupInvitation($from, $for, $groupID)
    {
        $acceptUrl = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('group-invit-accept', array('id' => $groupID));
        $declineUrl = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('group-invit-decline', array('id' => $groupID));
        $name = Group::where('id_trajet', '=', $groupID)->first()->nom;
        $content = <<<text
Vous avais etais invité a rejoindre le group <b>$name</b>.<br>
<button type="button" onclick="accept()">Accpeté</button><button type="button" onclick="decline()">Refusais</button>
<script>
const accept = () => {
    location.replace("$acceptUrl")
};
const decline = () => {
    location.replace("$declineUrl")
};
</script>
text;
        self::sendNotification($from, $for, $content);
    }

    public static function renderNotificationsList()
    {
        if (isset($_SESSION['mail'])) {
            $data = Notification::where('utilisateur', '=', $_SESSION['mail'])->get();
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