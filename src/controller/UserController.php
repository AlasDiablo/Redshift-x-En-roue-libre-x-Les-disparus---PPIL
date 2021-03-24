<?php

namespace ppil\controller;

use Exception;
use ppil\models\MotDePasseOublie;
use ppil\models\Utilisateur;
use ppil\util\AppContainer;
use ppil\util\EmailFactory;
use ppil\view\UserView;

class UserController
{

    public function modifierUtilisateur()
    {
        $nom = htmlentities($_POST['name']);
        $prenom = htmlentities($_POST['firstName']);
        $email = htmlentities($_POST['mail']);
        $mdp = htmlentities($_POST['password']);
        $tel = htmlentities($_POST['phone']);
        $sexe = htmlentities($_POST['sex']);
        $a_voiture = htmlentities($_POST['car']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !filter_var($nom) || !filter_var($prenom) || !filter_var($mdp) || !filter_var($tel) || !filter_var($sexe) || !filter_var($a_voiture)) {
            return UserView::erreurPost();
        }

        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT); //mdp 72 caracteres max (BCRYPT)

        $user = Utilisateur::modify([
            'email' => $email,
            'mdp' => $mdpHash,
            'nom' => $nom,
            'prenom' => $prenom,
            'tel' => $tel,
            'sexe' => $sexe,
            'a_voiture' => $a_voiture,
        ]);

        $_SESSION['mail'] = $email;
        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
    }


    public function creerUtilisateur()
    {
        $nom = filter_var($_POST['name'], FILTER_DEFAULT);
        $prenom = filter_var($_POST['firstName'], FILTER_DEFAULT);
        $email = filter_var($_POST['mail'], FILTER_DEFAULT);
        $mdp = filter_var($_POST['password'], FILTER_DEFAULT);
        $mdpconf = filter_var($_POST['confirmpassword'], FILTER_DEFAULT);
        $tel = filter_var($_POST['phone'], FILTER_DEFAULT);
        $sexe = filter_var($_POST['sex'], FILTER_DEFAULT);
        $a_voiture = filter_var($_POST['car'], FILTER_DEFAULT);

        if (!isset($nom) || !preg_match("#^[a-zA-Z]+$#", $nom) || strlen($nom < 2) || strlen($nom > 25)) {
            return UserView::erreurPost();
        }

        if (!isset($prenom) || !preg_match("#^[a-zA-Z]+$#", $prenom) || strlen($prenom < 2) || strlen($prenom > 25)) {
            return UserView::erreurPost();
        }

        if (!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return UserView::erreurPost();
        }

        if (!isset($mdp) || !isset($mdpconf) || ($mdp != $mdpconf) || strlen($mdp) < 7 || strlen($mdpconf) < 7) {
            return UserView::erreurPost();
        }

        if (!isset($tel) || strlen($tel) != 10 || !preg_match("#[0][6][- \.?]?([0-9][0-9][- \.?]?){4}$#", $tel)) {
            return UserView::erreurPost();
        }

        if (!isset($sexe)) {
            return UserView::erreurPost();
        }

        if (!isset($a_voiture)) {
            return UserView::erreurPost();
        }

        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT); //mdp 72 caracteres max (BCRYPT)

        $user = Utilisateur::create([
            'email' => $email,
            'mdp' => $mdpHash,
            'nom' => $nom,
            'prenom' => $prenom,
            'tel' => $tel,
            'sexe' => $sexe,
            'a_voiture' => $a_voiture,
            'note' => 5,
            'activer_notif' => 'N',
        ]);

        $_SESSION['mail'] = $email;

        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
    }

    public static function seConnecter()
    {
        // récuperation des valeurs POST
        $mail = filter_var($_POST['mail'], FILTER_DEFAULT);
        $mdp = filter_var($_POST['mdp'], FILTER_DEFAULT);

        // si aucun email ne correspond alors on renvoie la page d'erreur
        $value = Utilisateur::where('email', '=', $mail)->first();
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
        $value = Utilisateur::where("email", $mail)->first();
        if (!isset($value)) {
            return UserView::erreurPost();
        }

        // si une cle existe deja alors on la supprime
        $value = MotDePasseOublie::where("email", $mail)->first();
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
        $url = "https://" . $_SERVER['HTTP_HOST'] . AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('password-forgotten-key', array('key' => $token));

        $body = "cliquez sur l'url ci dessous pour réinitialisez votre mdp : " . $url;


        $nom = Utilisateur::select("nom")->where("email", "=", $mail)->first();

        // envoie du mail
        EmailFactory::envoieEmail($body, "reinitialisez le mdp", $mail, $nom);

        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
    }

    public static function recupererMdp($key)
    {
        // récuperation des valeurs POST
        $mdp1 = filter_var($_POST["password"], FILTER_DEFAULT);
        $mdp2 = filter_var($_POST["confirmpassword"], FILTER_DEFAULT);

        if (isset($mdp1) && isset($mdp2)) {
            if ($mdp1 == $mdp2 && strlen($mdp1) > 6) {

                // recuperation du mail grace a la cle
                $email = MotDePasseOublie::where('reset_key', '=', $key)->first()->email;
                if (!isset($email)) {
                    return UserView::erreurPost();
                }

                //change le mdp dans la base de donnée (pas sur ?) 
                $hash = password_hash($mdp1, PASSWORD_DEFAULT);
                Utilisateur::where("email", "=", $email)->update(["mdp" => $hash]);

                // suppression de la cle
                MotDePasseOublie::where("email", $email)->delete();

                // on redirige vers l'accueil
                $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
                header("Location: $url");
                exit();
            } else {
                return UserView::erreurPost();
            }
        } else {
            return UserView::erreurPost();
        }
    }

    private static function checkExistence($token)
    {
        return isset(MotDePasseOublie::select('reset_key')->where('reset_key', '=', $token)->first()->reset_key);
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
