<?php


namespace ppil\controller;


use ppil\models\Trajet;

class ElemTrajetPublicController
{
    public static function getTrajet($id)
    {
        $trajet = Trajet::where('id_trajet', '=', $id)->first();
        return $trajet;
    }

    public static function ListeTrajet()
    {

    }
}