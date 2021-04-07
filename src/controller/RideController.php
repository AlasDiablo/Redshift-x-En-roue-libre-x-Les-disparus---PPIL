<?php

namespace ppil\controller;

use ppil\models\Passager;
use ppil\models\Trajet;
use ppil\models\VilleIntermediaire;

class RideController
{

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


}
