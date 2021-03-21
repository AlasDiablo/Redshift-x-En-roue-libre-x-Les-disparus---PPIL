<?php

namespace ppil\controller;

use ppil\models\Utilisateur;
use ppil\util\AppContainer;

class UserController
{
    public static function seConnecter()
    {
        $mail = filter_var($_POST['mail'], FILTER_DEFAULT);
        $mdp = filter_var($_POST['mdp'], FILTER_DEFAULT);

        $value = Utilisateur::where('email', '=', $mail)->get()->first();
        if (!isset($value)) {
            return UserView::erreurPost();
        }

        $bddMdp = Utilisateur::select('mdp')->where('email', '=', $mail)->first()->mdp;
        if (!password_verify($mdp, $bddMdp)) {
            return UserView::erreurPost();
        }

        $_SESSION['mail'] = $mail;
        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
    }

    public static function seDeconnecter()
    {
        session_unset(); 
        session_destroy(); 
        //ajouter l'url de la page d'acceuille 
        header("Location : index ?")
        exit(); 

    }

    public static function mdpOublie()
    {

        
        $mail = filter_var($_POST["mail"], FILTER_DEFAULT);
        
        $value = Utilisateur::where("email", $mail)->get()->first();
        if (!isset($value)) {
            return UserView::erreurPost();
        }


        $body = "cliquez sur l'url ci dessous pour réinitialisez votre mdp : ...";
        $nom = Utilisateur::select("nom")->where("email", "=", $mail)->first(); 


        envoieEmail($body, "reinitialisez le mdp", $mail, $nom  )
    }


    public static function recupererMdp(){


        $mdp1 = filter_var($_POST["mdp?"], FILTER_DEFAULT);
        $mdp2 = filter_var($_POST["mdp?"], FILTER_DEFAULT);

        if(isset($mdp1) && isset($mdp2)){


            if($mdp1 == $mdp2 /*&& verification des contraintes mdp*/){


                //Aucune idée de comment recuperer l'email de la personne 

                //changer le mdp dans la base de donnée, pas sur ? 
                Utilisateur::where("email", "=", $mail)->update(["mdp" => $mdp1]); 


                //rediriger vers la page de connexion 
                header("Location : ?"); 

            }
        }
    }


    //peut etre mettre la fonction dans un fichier a part ? 
    private static function envoieEmail($body, $title, $email, $name){
    
    $mail = new PHPMailer();

    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->IsSMTP();
    $mail->Mailer     = "smtp";
    $mail->SMTPDebug  = 1;
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587; 
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   = "chiken.killer.lel@gmail.com"; 
    $mail->Password   = file_get_contents(Reference::EMAIL_INI); // C'est le fichier avec le mdp du compte gmail
    $mail->setFrom("no-repley@sharemyride.fr", "ShareMyRide"); // Qui l'envoie

    $mail->addAddress($email, $name); 
    $mail->isHTML(false); 
    $mail->setLanguage("fr");
    $mail->CharSet    = "UTF-8"; 
    $mail->Subject    = $title; 
    $mail->Body       = $body;
    if(!$mail->send()){
        echo "erreur envoie mail";
    }
}


}