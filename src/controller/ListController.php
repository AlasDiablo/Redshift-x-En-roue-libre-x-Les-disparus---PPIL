<?php

namespace ppil\controller;

use DateTime;
use ppil\models\Trajet;
use ppil\models\VillesFrance;
use ppil\view\RideView;

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
            return ListView::erreurPost();
        }
        $value = VillesFrance::where('ville_nom', '=', $villeArrive)->first();
        if (!isset($value)) {
            return ListView::erreurPost();
        }
        $dateFormat = DateTime::createFromFormat('d/m/Y', $date);
        if (!$dateFormat) {
            return ListView::erreurPost();
        }
        if (strcmp($ordre, 'date') != 0 || strcmp($ordre, 'ville_depart') || strcmp($ordre, 'ville_arrivee')) {
            return ListView::erreurPost();
        }

        // filtrer & trier
        $trajets = Trajet::where('ville_depart', '=', $villeDepart)->
                           where('ville_arrivee', '=', $villeArrive)->
                           where('date', '=', $date)->
                           whereNull('id_groupe')->orderBy($ordre)->get();

        // envoie de la liste
        return RideView::renderRideList($trajets);
    }

    public static function listPublic()
    {
        return RideView::renderRideList(Trajet::where('id_groupe', '=', null)->get());
    }
}
