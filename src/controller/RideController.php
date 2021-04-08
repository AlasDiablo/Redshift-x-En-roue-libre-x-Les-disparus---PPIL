<?php

namespace ppil\controller;

use ppil\models\Passager;
use ppil\models\Trajet;
use ppil\models\VilleFrance;
use ppil\models\VilleIntermediaire;
use ppil\util\AppContainer;
use ppil\view\RideView;
use ppil\view\ViewRendering;

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

    public static function displayRide($id) {
        $data = array();
        $ride = self::getRide($id);

        $data['ville_depart'] = $ride->ville_depart;
        $data['ville_arrivee'] = $ride->ville_arrivee;
        $data['nbr_passager'] = $ride->date;
        $data['nbr_passager_occup'] = self::getNbPlaceOccupee($id);
        $data['heure_depart'] = $ride->heure_depart;
        $data['prix'] = $ride->prix;
        $data['date'] = $ride->date;
        $data['lieuxRDV'] = $ride->lieuxRDV;
        $data['commentaires'] = $ride->commentaires;
        $data['ville_intermediere'] = self::getEtape($id);
        $data['passagers'] = self::getPassager($id);

        return RideView::renderUser($data);
    }

    public static function creerTrajet(){
        $villeDepart = filter_var($_POST['departure'], FILTER_DEFAULT);
        $villeArrivee = filter_var($_POST['arrival'], FILTER_DEFAULT);
        $date = filter_var($_POST['date'], FILTER_DEFAULT);
        $nbPassagers = filter_var($_POST['passengers'], FILTER_DEFAULT);
        $heureDepart = filter_var($_POST['hour'], FILTER_DEFAULT);
        $prix = filter_var($_POST['price'], FILTER_DEFAULT);

        // A changer en fonction de comment les etapes intermédiaires ont été intégré dans le formulaire (array...)
        $etapeInter = array();
        $i = 0;
        foreach ($_POST['stages'] as $stage) {
            $etapeInter[$i] = filter_var($stage, FILTER_DEFAULT);
            $i++;
        }

        // Lieu de rendez vous c'est quoi ? C4est meme pas dans la base de donées / j'en ai jamais entendu parler

        $commentaires = filter_var($_POST['comments'], FILTER_DEFAULT);

        $matches = null;

        // Messages d'erreurs pour la ville de départ
        if (!isset($villeDepart)){
            return ViewRendering::renderError("Vous n'avez pas mis de ville de départ.");
        }
        if(preg_match('/^[a-zA-Z]+$/', $villeDepart, $matches, PREG_OFFSET_CAPTURE, 0) == false){
            return ViewRendering::renderError("Le nom de la ville de départ ne peut pas comporter de chiffre.");
        }
        if(!isset(VilleFrance::where('ville_nom', '=', $villeDepart)->first()->ville_nom)){
            return ViewRendering::renderError("La ville de départ n'existe pas dans la base de données.");
        }

        // Messages d'erreurs pour la ville d'arrivée
        if (!isset($villeArrivee)){
            return ViewRendering::renderError("Vous n'avez pas mis de ville d'arrivée.");
        }
        if(preg_match('/^[a-zA-Z]+$/', $villeArrivee, $matches, PREG_OFFSET_CAPTURE, 0) == false){
            return ViewRendering::renderError("Le nom de la ville d'arrivée ne peut pas comporter de chiffre.");
        }
        if(!isset(VilleFrance::where('ville_nom', '=', $villeArrivee)->first()->ville_nom)){
            return ViewRendering::renderError("La ville d'arrivée n'existe pas dans la base de données.");
        }

        // Messages d'erreurs pour la date de départ
        if (!isset($date)){
            return ViewRendering::renderError("Vous n'avez pas mis de date de départ.");
        }
        if(!self::validateDateDepart($date, "Y-m-d")){
            return ViewRendering::renderError("Date de départ invalide.");
        }

        // Messages d'erreurs pour le nombre de passagers
        if(!isset($nbPassagers)){
            return ViewRendering::renderError("Vous n'avez pas mis le nombre de passagers pour le trajet.");
        }
        if(preg_match('/^[1-9]+[0-9]*$/', $nbPassagers, $matches, PREG_OFFSET_CAPTURE, 0) == false){
            return ViewRendering::renderError("Le nombre de passagers doit être un nombre entier.");
        }

        // Messages d'erreurs pour l'heure de départ
        if (!isset($heureDepart)){
            return ViewRendering::renderError("Vous n'avez pas mis d'heure de départ.");
        }
        if(!self::validateDateDepart($date . " " . $heureDepart, "Y-m-d hh:mm")){
            return ViewRendering::renderError("Heure de départ invalide.");
        }

        // Messages d'erreurs pour le nombre de passagers
        if(!isset($prix)){
            return ViewRendering::renderError("Vous n'avez pas mis le nombre de passagers pour le trajet.");
        }
        if(!(filter_var($prix, FILTER_VALIDATE_FLOAT) || filter_var($prix, FILTER_VALIDATE_INT))){
            return ViewRendering::renderError("Le prix doit être un nombre entier ou reel.");
        }
        if($prix < 0){
            return ViewRendering::renderError("Le prix doit être supérieur ou egal à zero.");
        }

        // Messages d'erreurs pour les etapes intermédiaires
//        if (isset($etapeInter) && $etapeInter!=""){
//            if(preg_match('/^[a-zA-Z]+$/', $etapeInter, $matches, PREG_OFFSET_CAPTURE, 0) == false){
//                return ViewRendering::renderError("Le nom d'une étape intermédiaire: " . $etapeInter . " ne peut pas comporter de chiffre.");
//            }
//            if(!isset(VilleIntermediaire::where('ville', '=', $etapeInter)->first()->ville_nom)){
//                return ViewRendering::renderError("L'étape intermédiaire: " . $etapeInter . " n'existe pas dans la base de données.");
//            }
//        }

        $ride = new Trajet();
        $ride->date = $date;
        $ride->ville_depart = $villeDepart;
        $ride->ville_arrivee = $villeArrivee;
        $ride->heure_depart = $heureDepart;
        $ride->email_conducteur = $_SESSION['mail'];
        $ride->nbr_passager = $nbPassagers;
        $ride->prix = $prix;

        $id = Trajet::max('id_trajet');
        if (isset($id)) $id++;
        else $id = 0;

        $ride->id_trajet = $id;
        $ride->save();

        foreach ($etapeInter as $etape) {
            $villeIntermediaire = new VilleIntermediaire();
            $villeIntermediaire->id_trajet = $id;
            $villeIntermediaire->ville = $etape;
            $villeIntermediaire->save();
        }

        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
    }

    private static function validateDateDepart($date, $format)
    {
        $date_now = new \DateTime();
        return strtotime($date_now->format($format)) <= strtotime($date) ;
    }
}
