<?php

namespace ppil\controller;

use Exception;
use ppil\models\MotDePasseOublie;
use ppil\models\Utilisateur;
use ppil\util\AppContainer;
use ppil\util\EmailFactory;
use ppil\view\UserView;
use ppil\view\ViewRendering;

class UserController
{

    public static function modifierUtilisateur()
    {
        $nom = filter_var($_POST['name'], FILTER_DEFAULT);
        $prenom = filter_var($_POST['firstName'], FILTER_DEFAULT);
        $tel = filter_var($_POST['phone'], FILTER_DEFAULT);
        $sexe = filter_var($_POST['sex'], FILTER_DEFAULT);
        $a_voiture = filter_var($_POST['car']);
        if (!$nom || !$prenom || !$tel || !$a_voiture || !$sexe) {
            return UserView::erreurPost("Donnée invalid");
        }

        $user = Utilisateur::where('email', '=', $_SESSION['mail'])->first();
        $user->nom = $nom;
        $user->prenom = $prenom;
        $user->tel = $tel;
        $user->a_voiture = $a_voiture == 'yes' ? 'O' : 'N';
        $user->sexe = $sexe;
        $user->save();


        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
    }

    public static function modifierProfilVue(): string
    {
        $data = Utilisateur::select('email', 'nom', 'prenom', 'tel', 'sexe', 'a_voiture')->where('email', '=', $_SESSION['mail'])->first();

        return UserView::modifierProfil($data);
    }


    public static function creerUtilisateur()
    {
        $nom = filter_var($_POST['name'], FILTER_DEFAULT);
        $prenom = filter_var($_POST['firstName'], FILTER_DEFAULT);
        $email = filter_var($_POST['mail'], FILTER_DEFAULT);
        $mdp = filter_var($_POST['password'], FILTER_DEFAULT);
        $mdpconf = filter_var($_POST['confirmpassword'], FILTER_DEFAULT);
        $tel = filter_var($_POST['phone'], FILTER_DEFAULT);
        $sexe = filter_var($_POST['sex'], FILTER_DEFAULT);
        $a_voiture = filter_var($_POST['car'], FILTER_DEFAULT);

        print_r($_POST);

        $matches = null;
        if (!isset($nom) || preg_match('/^[a-zA-Z]+$/', $nom, $matches, PREG_OFFSET_CAPTURE, 0) == false || strlen($nom) < 2 || strlen($nom) > 25) {
            return UserView::erreurPost("Nom invalid");
        }

        if (!isset($prenom) || !preg_match('/^[a-zA-Z]+$/', $prenom, $matches, PREG_OFFSET_CAPTURE, 0) || strlen($prenom) < 2 || strlen($prenom) > 25) {
            return UserView::erreurPost("Prenom invalid");
        }

        if (!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return UserView::erreurPost("Email invalid");
        }

        if (!isset($mdp) || !isset($mdpconf) || ($mdp != $mdpconf) || strlen($mdp) < 7 || strlen($mdpconf) < 7) {
            return UserView::erreurPost("Mot de passe invalid");
        }

        if (!isset($tel) || strlen($tel) != 10 || !preg_match("#[0][6][- \.?]?([0-9][0-9][- \.?]?){4}$#", $tel)) {
            return UserView::erreurPost("Tel invalid");
        }

        if (!isset($sexe)) {
            return UserView::erreurPost("Sexe invalid");
        }

        if (!isset($a_voiture)) {
            return UserView::erreurPost("Voiture invalid");
        }

        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT); //mdp 72 caracteres max (BCRYPT)

        $user = new Utilisateur();
        $user->email = $email;
        $user->mdp = $mdpHash;
        $user->nom = $nom;
        $user->prenom = $prenom;
        $user->tel = $tel;
        $user->sexe = $sexe;
        $user->a_voiture = $a_voiture == 'yes' ? 'O' : 'N';
        $user->note = 5;
        $user->activer_notif = 'O';
        $user->save();

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

        $body = "Cliquez sur l'url ci-dessous pour réinitialisez votre mdp : " . $url;


        $nom = Utilisateur::select("nom")->where("email", "=", $mail)->first()->nom;

        // envoie du mail
        EmailFactory::envoieEmail($body, "Réinitialisez le mot de passe", $mail, $nom);

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
