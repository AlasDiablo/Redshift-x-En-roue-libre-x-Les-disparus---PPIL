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

    public static function filterList()
    {
        // recuperation des parametres
        $villeDepart = filter_var($_POST['villeDepart'], FILTER_DEFAULT);
        $villeArrive = filter_var($_POST['villeArrive'], FILTER_DEFAULT);
        $date = filter_var($_POST['date'], FILTER_DEFAULT);
        $ordre = filter_var($_POST['ordre'], FILTER_DEFAULT);

        // faire les verifs
        $value = VillesFrance::where('ville_nom', '=', $villeDepart)->first();
        if (!isset($value)) {
            return ViewRendering::renderError();
        }
        $value = VillesFrance::where('ville_nom', '=', $villeArrive)->first();
        if (!isset($value)) {
            return ViewRendering::renderError();
        }
        $dateFormat = DateTime::createFromFormat('d/m/Y', $date);
        if (!$dateFormat) {
            return ViewRendering::renderError();
        }
        if (strcmp($ordre, 'date') != 0 || strcmp($ordre, 'ville_depart') || strcmp($ordre, 'ville_arrivee')) {
            return ViewRendering::renderError();
        }

        // filtrer & trier
        $trajets = Trajet::where('ville_depart', '=', $villeDepart)->
                           where('ville_arrivee', '=', $villeArrive)->
                           where('date', '=', $date)->
                           whereNull('id_groupe')->orderBy($ordre)->get();

        // envoie de la liste
        return RideView::renderRideList($trajets, '', '');
    }

    public static function mesTrajets()
    {
        $rides = Utilisateur::where("email", '=', $_SESSION['mail'])->first()->mesTrajets()->get();
        return RideView::renderRideList($rides, 'Mes trajet', 'dans mes offres de trajet');
    }

    public static function trajetsParticipes()
    {
        $rides = Utilisateur::where("email", '=', $_SESSION['mail'])->first()->mesParticipation()->get();
        return RideView::renderRideList($rides, 'Mes trajet', 'dans mes offres de trajet');
    }

    public static function listPublic()
    {
        return RideView::renderRideList(Trajet::where('id_groupe', '=', null)->get(), 'Liste des trajet public', 'des offres de trajets');
    }
}
