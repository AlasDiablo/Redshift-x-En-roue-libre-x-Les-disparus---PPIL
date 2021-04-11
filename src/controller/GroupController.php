<?php
namespace ppil\controller ;
use ppil\models\Membre ;
use ppil\models\Trajet;
use ppil\models\Utilisateur;
use ppil\view\ViewRendering;

class GroupController

{
    public function deleteMember($idGroup)
    {
        //recuperation des valeurs post
        $mail = filter_var($_POST["mail"], FILTER_DEFAULT);

        //recuperer les trajets du membre
        $rides = Utilisateur::where('email', '=', $mail)->where('id_groupe', '=', $idGroup)->first()->mesParticipation()->get();
        $validDeletion = true;
        foreach ($rides as $ride) {
            $date = date("d-m-Y H:i");
            $match_date = date('d-m-Y H:i:s', strtotime($ride->date . ' ' . $ride->heure_depart));
            //verifier qu'il ne participe a aucun trajet de - 24h
            if (strtotime("-1 day", $date) >= $match_date) {
                $validDeletion = false;
            }

        }

        //supprimer le membre du groupe
        if($validDeletion) {
            Membre::where('email_member', '=', $mail)->where('id_groupe', '=', $idGroup)->delete();

            //supprimer ses trajets
            Trajet::where('email_conducteur', '=', $mail)->where('id_groupe', '=', $idGroup)->delete();
            Utilisateur::where('email', '=', $mail)->first()->mesParticipation()->where('id_groupe', '=', $idGroup)->delete();

        }else{

            return ViewRendering::renderError("des trajets de - de 24h existe");
        }

        exit();
    }


    public function addMember($idGroup)
    {
        //C'EST TOTALEMENT FOIREUX

        //recuperation des valeurs post
        $mail = filter_var($_POST["mail"], FILTER_DEFAULT);

        //ajout du membre
        $member = new Member();
        $member->email_membre = $mail;
        $member->id_groupe = idgroupe;
        $member->reponse = false;

    }
}