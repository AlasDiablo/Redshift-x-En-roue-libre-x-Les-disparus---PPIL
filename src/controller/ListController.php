<?php

namespace ppil\controller;

use DateTime;
use ppil\models\Trajet;
use ppil\models\Utilisateur;
use ppil\models\VillesFrance;
use ppil\view\RideView;
use ppil\view\ViewRendering;

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

        if (isset($_GET['ordre'])) if ($_GET['ordre'] != '') {
            $ordre = filter_var($_GET['ordre'], FILTER_DEFAULT);
            if ($ordre =='date'|| $ordre == 'ville_depart' || $ordre == 'ville_arrivee') {
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

    public static function listPublic()
    {
        $filteredRide = self::applyFilter(Trajet::whereNull('id_groupe'));
        return RideView::renderRideList($filteredRide, 'Liste des trajet public', 'des offres de trajets');
    }
}
