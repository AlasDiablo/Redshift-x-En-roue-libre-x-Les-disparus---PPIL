<?php

namespace ppil\controller;

use Exception;
use ppil\models\MotDePasseOublie;
use ppil\models\Utilisateur;
use ppil\util\AppContainer;
use ppil\util\EmailFactory;

class UserController
{
    public static function seConnecter()
    {
        // récuperation des valeurs POST
        $mail = filter_var($_POST['mail'], FILTER_DEFAULT);
        $mdp = filter_var($_POST['mdp'], FILTER_DEFAULT);

        // si aucun email ne correspond alors on renvoie la page d'erreur
        $value = Utilisateur::where('email', '=', $mail)->get()->first();
        if (!isset($value)) {
            return UserView::erreurPost();
        }

        // si le mdp ne correspond pas alors on renvoie la page d'erreur
        $bddMdp = Utilisateur::select('mdp')->where('email', '=', $mail)->first()->mdp;
        if (!password_verify($mdp, $bddMdp)) {
            return UserView::erreurPost();
        }

        // on met a jour la session
        $_SESSION['mail'] = $mail;

        // on redirige vers l'accueil
        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
    }

    public static function seDeconnecter()
    {
        // on detruit la session
        session_unset();
        session_destroy();

        // on redirige vers l'accueil
        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
    }

    public static function mdpOublie()
    {
        // récuperation des valeurs POST
        $mail = filter_var($_POST["mail"], FILTER_DEFAULT);

        // si aucun email ne correspond alors on renvoie la page d'erreur
        $value = Utilisateur::where("email", $mail)->get()->first();
        if (!isset($value)) {
            return UserView::erreurPost();
        }

        // si une cle existe deja alors on la supprime
        $value = MotDePasseOublie::where("email", $mail)->get()->first();
        if (isset($value)) {
            MotDePasseOublie::where("email", $mail)->delete();
        }

        // on genere une nouvelle cle
        $token = UserController::getToken();
        $mdpOublie = new MotDePasseOublie;
        $mdpOublie->email = $mail;
        $mdpOublie->reset_key = $token;
        $mdpOublie->save();

        // creation du mail
        $body = "cliquez sur l'url ci dessous pour réinitialisez votre mdp : https://".$_SERVER['HTTP_HOST']."/accounts/password-forgotten.php?key=".$token;
        $nom = Utilisateur::select("nom")->where("email", "=", $mail)->first();

        // envoie du mail
        EmailFactory::envoieEmail($body, "reinitialisez le mdp", $mail, $nom);
    }

    public static function recupererMdp()
    {
        // récuperation des valeurs POST
        $mdp1 = filter_var($_POST["mdp1"], FILTER_DEFAULT);
        $mdp2 = filter_var($_POST["mdp2"], FILTER_DEFAULT);
        $key = filter_var($_POST["key"], FILTER_DEFAULT);

        if (isset($mdp1) && isset($mdp2)) {
            if ($mdp1 == $mdp2 && strlen($mdp1) > 6 && preg_match('/[A-Z]/', $mdp1) && preg_match('/[0-9]/', $mdp1)) {

                // recuperation du mail grace a la cle
                $mail = MotDePasseOublie::where('reset_key', '=', $key)->get()->first();
                if(!isset($email)) {
                    return UserView::erreurPost();
                }

                //change le mdp dans la base de donnée (pas sur ?) 
                $hash = password_hash($mdp1, PASSWORD_DEFAULT);
                Utilisateur::where("email", "=", $mail)->update(["mdp" => $hash]);

                // suppression de la cle
                MotDePasseOublie::where("email", $mail)->delete();

                // on redirige vers l'accueil
                $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
                header("Location: $url");
                exit();
            }else {
                return UserView::erreurPost();
            }
        }else {
            return UserView::erreurPost();
        }
    }

    private static function checkExistence($token)
    {
        return isset(MotDePasseOublie::select('rest_key')->where('rest_key', '=', $token)->first()->rest_key);
    }

    private static function getToken()
    {
        do {
            try {
                $token = bin2hex(random_bytes(32));
            } catch (Exception $e) {
                echo "Error 500"; // Erreur 500 a affiché plus proprement
                exit(1);
            }
        } while (self::checkExistence($token) != false);
        return $token;
    }
}
