<?php

namespace ppil\controller;

use DateTime;
use ppil\models\Trajet;
use ppil\models\Utilisateur;
use ppil\view\RideView;

class ListController
{

    public static function applyFilter($rides)
    {
        if (isset($_GET['depart'])) if ($_GET['depart'] != '') {
            $villeDepart = filter_var($_GET['depart'], FILTER_DEFAULT);
            $rides = $rides->where('ville_depart', '=', $villeDepart);
        }

        if (isset($_GET['arrive'])) if ($_GET['arrive'] != '') {
            $villeArrive = filter_var($_GET['arrive'], FILTER_DEFAULT);
            $rides = $rides->where('ville_arrivee', '=', $villeArrive);
        }

        if (isset($_GET['date'])) if ($_GET['date'] != '') {
            $date = filter_var($_GET['date'], FILTER_DEFAULT);
            $dateFormat = DateTime::createFromFormat('d/m/Y', $date);
            if ($dateFormat != false) {
                $rides = $rides->where('date', '=', $date);
            }
        }

        if (isset($_GET['order'])) if ($_GET['order'] != '') {
            $ordre = filter_var($_GET['order'], FILTER_DEFAULT);
            if ($ordre == 'date' || $ordre == 'ville_depart' || $ordre == 'ville_arrivee' || $ordre == 'prix') {
                $rides = $rides->orderBy($ordre);
            }
        }

        return $rides->get();
    }

    public static function mesTrajets()
    {
        $filteredRide = self::applyFilter(Utilisateur::where("email", '=', $_SESSION['mail'])->first()->mesTrajets());
        return RideView::renderRideList($filteredRide, 'Mes trajet', 'dans mes offres de trajet');
    }

    public static function trajetsParticipes()
    {
        $rides = self::applyFilter(Utilisateur::where("email", '=', $_SESSION['mail'])->first()->mesParticipation());
        return RideView::renderRideList($rides, 'Mes trajet', 'dans mes participation');
    }

    public static function listPublic()
    {
        $filteredRide = self::applyFilter(Trajet::whereNull('id_groupe'));
        return RideView::renderRideList($filteredRide, 'Liste des trajet public', 'des offres de trajets');
    }

    public static function listPrivate()
    {
        $groups = array();
        foreach (Utilisateur::where("email", '=', $_SESSION['mail'])->first()->memberDe()->get() as $group)
            array_push($groups, $group->id_groupe);

        $rides = self::applyFilter(Trajet::whereIn('id_groupe', $groups));

        return RideView::renderRideList($rides, 'Liste des trajet privé', 'des offres de trajets privés');

    }
}
