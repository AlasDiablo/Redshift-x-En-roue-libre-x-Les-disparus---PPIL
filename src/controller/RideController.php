<?php

namespace ppil\controller;

use ppil\models\Utilisateur;
use ppil\models\Passager;
use ppil\models\Trajet;
use ppil\models\VilleIntermediaire;
use ppil\view\RideView;

class RideController
{

    public static function mesTrajets()
    {
        $user = Utilisateur::where("email", '=', $_SESSION['mail'])->first();
        $rides = Utilisateur::mesTrajets();
        RideView::renderMinRide($rides);
    }

    public static function getRide($id)
    {
        return Trajet::where('id_trajet', '=', $id)->first();
    }

    public static function getEtape($id)
    {
        return Villeintermediaire::where('id_trajet', '=', $id)->get();
    }

    public static function getPassager($id)
    {
        return Passager::where('id_trajet', '=', $id)->get();
    }

    public static function getNbPlaceOccupee($id)
    {
        return count(self::getPassager($id));
    }

    public static function displayRide($id) {
        $data = array();
        $ride = self::getRide($id);

        $data['ville_depart'] = $ride->ville_depart;
        $data['ville_arrivee'] = $ride->ville_arrivee;
        $data['nbr_passager'] = $ride->date;
        $data['nbr_passager_occup'] = self::getNbPlaceOccupee($id);
        $data['heure_depart'] = $ride->heure_depart;
        $data['prix'] = $ride->prix;
        $data['lieuxRDV'] = $ride->lieuxRDV;
        $data['commentaires'] = $ride->commentaires;
        $data['ville_intermediere'] = self::getEtape($id);
        $data['passagers'] = self::getPassager($id);

        return RideView::renderUser($data);
    }

}
