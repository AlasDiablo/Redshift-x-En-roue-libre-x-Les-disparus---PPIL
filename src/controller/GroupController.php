<?php
namespace ppil\controller ;
use ppil\models\Groupe;
use ppil\models\Membre ;
use ppil\models\Trajet;
use ppil\models\Utilisateur;
use ppil\util\ImageChecker;
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
        $member->id_groupe = $idGroup;
        $member->reponse = false;
        $member->save();

    }

    public function creerGroupe(){

        //recuperation des valeurs post
        $mail = filter_var($_POST["mail"], FILTER_DEFAULT);


        $id = Groupe::max('id_group');
        if(isset($id)) $id++;
        else $id = 0;

        $nom = filter_var($_POST['groupname-form'], FILTER_DEFAULT);

        $image = ImageChecker::checkAvatar('groups' . DIRECTORY_SEPARATOR . basename(md5($nom . $id)));

        //message d'erreur image
        if($image == null){
            return ViewRendering::renderError("Votre image de groupe doit etre une image et avoir une taille de 400px par 400 px et faire un maximum de 20 Mo.");

        }


        //message d'erreur nom
        if(!isset($nom)){
            return ViewRendering::renderError("Vous n'avez pas mis de nom de groupe");
        }

        if(strlen($nom) <= 3 || strlen($nom) >= 25 ){
            return ViewRendering::renderError("Le nom du groupe doit avoir une taille de minimum 3 et de maximum 25");
        }

        if(!isset(Groupe::where('nom', '=', $nom)->first()->nom)){
            return ViewRendering::renderError("Le nom du groupe existe déjà");

        }



        $group = new Groupe();
        $group->id_group = $id;
        $group->nom = $nom;
        $group->email_createur = $mail;
        $group->save();
        if($image != 'no_image') $group->url_img = '/uploads/' . $image;



        addMember($id);


        exit();
    }
}