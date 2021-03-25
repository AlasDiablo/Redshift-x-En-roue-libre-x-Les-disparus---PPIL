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

    public static function modifierUtilisateur()
    {
        $nom = filter_var($_POST['name'], FILTER_DEFAULT);
        $prenom = filter_var($_POST['firstName'], FILTER_DEFAULT);
        $tel = filter_var($_POST['phone'], FILTER_DEFAULT);
        $mail = filter_var($_POST['mail'], FILTER_DEFAULT);
        $ancienmdp = filter_var($_POST['oldpassword'], FILTER_DEFAULT);
        $nouveaumdp = filter_var($_POST['newpassword'], FILTER_DEFAULT);
        $confnouvmdp = filter_var($_POST['confirmnewpassword'], FILTER_DEFAULT);
        $sexe = filter_var($_POST['sex'], FILTER_DEFAULT);
        $a_voiture = filter_var($_POST['car']);

        $matches = null;

        #Messages d'erreurs pour le nom
        if (!isset($nom)){
            return UserView::erreurPost("Vous n'avez pas mis votre nom.");
        }

        if(preg_match('/^[a-zA-Z]+$/', $nom, $matches, PREG_OFFSET_CAPTURE, 0) == false){
            return UserView::erreurPost("Votre nom ne peut pas comporter de chiffre.");
        }

        if(strlen($nom) < 2 || strlen($nom) > 25){
            return UserView::erreurPost("Votre nom ne peut pas comporter aussi peut ou autant de lettre (entre 2 et 25).");
        }

        #Messages d'erreurs pour le prénom
        if (!isset($prenom)) {
            return UserView::erreurPost("Vous n'avez pas mis votre prénom.");
        }

        if(!preg_match('/^[a-zA-Z]+$/', $prenom, $matches, PREG_OFFSET_CAPTURE, 0)){
            return UserView::erreurPost("Votre prénom ne peut pas comporter de chiffre.");
        }

        if(strlen($prenom) < 2 || strlen($prenom) > 25){
            return UserView::erreurPost("Votre prénom ne peut pas comporter aussi peut ou autant de lettre (entre 2 et 25).");
        }

        #Messages d'erreurs pour le mot de passe

            // si le mdp ne correspond pas alors on renvoie la page d'erreur
        $bddMdp = Utilisateur::select('mdp')->where('email', '=', $mail)->first()->mdp;
        if (!password_verify($ancienmdp, $bddMdp)) {
            return UserView::erreurPost("Le mot de passe ne correspond pas à votre ancien mot de passe.");
        }

        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{7,})/', $nouveaumdp, $matches, PREG_OFFSET_CAPTURE, 0)){
            return UserView::erreurPost("Votre mot de passe doit comporter au moins 7 caractères dont au moins une majuscule, et un chiffre.");
        }

        if(($nouveaumdp != $confnouvmdp)){
            return UserView::erreurPost("Le mot de passe de confirmation est différent du mot de passe.");
        }

        if(($nouveaumdp == $ancienmdp)){
            return UserView::erreurPost("Le nouveau mot de passe doit être différent de l'ancien mot de passe.");
        }

        #Messages d'erreurs pour le téléphone
        if (!isset($tel)) {
            return UserView::erreurPost("Vous n'avez pas mis de numéro de téléphone.");
        }

        if(strlen($tel) != 10){
            return UserView::erreurPost("Un numéro de téléphone contient 10 chiffres.");
        }

        if(!preg_match("#[0]([6]|[7])[- .?]?([0-9][0-9][- .?]?){4}$#", $tel)){
            return UserView::erreurPost("Le numéro de téléphone doit commencer par 06 ou 07.");
        }

        #Message d'erreur pour le sexe
        if (!isset($sexe)) {
            return UserView::erreurPost("Vous n'avez pas indiqué votre sexe.");
        }

        #Message d'erreur pour le véhicule
        if (!isset($a_voiture)) {
            return UserView::erreurPost("Vous n'avez pas indiqué si vous aviez une voiture ou non.");
        }

        $oldMdpHash = password_hash($ancienmdp, PASSWORD_DEFAULT); //mdp 72 caracteres max (BCRYPT)
        $newMdpHash = password_hash($nouveaumdp, PASSWORD_DEFAULT); //mdp 72 caracteres max (BCRYPT)

        $user = Utilisateur::where('email', '=', $_SESSION['mail'])->first();
        $user->nom = $nom;
        $user->prenom = $prenom;
        $user->tel = $tel;

        if(strlen($nouveaumdp) == 0){
            $user->mdp = $oldMdpHash;
        }else{
            $user->mdp = $newMdpHash;
        }

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

        $matches = null;

        #Messages d'erreurs pour le nom
        if (!isset($nom)){
            return UserView::erreurPost("Vous n'avez pas mis votre nom.");
        }

        if(preg_match('/^[a-zA-Z]+$/', $nom, $matches, PREG_OFFSET_CAPTURE, 0) == false){
            return UserView::erreurPost("Votre nom ne peut pas comporter de chiffre.");
        }

        if(strlen($nom) < 2 || strlen($nom) > 25){
            return UserView::erreurPost("Votre nom ne peut pas comporter aussi peut ou autant de lettre (entre 2 et 25).");
        }

        #Messages d'erreurs pour le prénom
        if (!isset($prenom)) {
            return UserView::erreurPost("Vous n'avez pas mis votre prénom.");
        }

        if(!preg_match('/^[a-zA-Z]+$/', $prenom, $matches, PREG_OFFSET_CAPTURE, 0)){
            return UserView::erreurPost("Votre prénom ne peut pas comporter de chiffre.");
        }

        if(strlen($prenom) < 2 || strlen($prenom) > 25){
            return UserView::erreurPost("Votre prénom ne peut pas comporter aussi peut ou autant de lettre (entre 2 et 25).");
        }

        #Messages d'erreurs pour l'adresse éléctronique
        if (!isset($email)){
            return UserView::erreurPost("Vous n'avez pas mis d'email.");
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return UserView::erreurPost("L'email que vous avez noté n'est pas valable (ex: abc@coucou.fr).");
        }

        #Messages d'erreurs pour le mot de passe
        if (!isset($mdp)){
            return UserView::erreurPost("Vous n'avez pas mis de mot de passe");
        }

        if(!isset($mdpconf)){
            return UserView::erreurPost("Vous n'avez pas mis de mot de passe de confirmation");
        }

        if(!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{7,})#", $mdp, $matches, PREG_OFFSET_CAPTURE, 0)){
            return UserView::erreurPost("Votre mot de passe doit comporter au moins 7 caractères dont au moins une majuscule, et un chiffre.");
        }

        if(($mdp != $mdpconf)){
            return UserView::erreurPost("Le mot de passe de confirmation est différent du mot de passe.");
        }

        #Messages d'erreurs pour le téléphone
        if (!isset($tel)) {
            return UserView::erreurPost("Vous n'avez pas mis de numéro de téléphone.");
        }

        if(strlen($tel) != 10){
            return UserView::erreurPost("Un numéro de téléphone contient 10 chiffres.");
        }

        if(!preg_match("#[0]([6]|[7])[- .?]?([0-9][0-9][- .?]?){4}$#", $tel)){
            return UserView::erreurPost("Le numéro de téléphone doit commencer par \"06\" ou \"07\".");
        }

        #Message d'erreur pour le sexe
        if (!isset($sexe)) {
            return UserView::erreurPost("Vous n'avez pas indiqué votre sexe.");
        }

        #Message d'erreur pour le véhicule
        if (!isset($a_voiture)) {
            return UserView::erreurPost("Vous n'avez pas indiqué si vous aviez une voiture ou non.");
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

        $body = "Cliquez sur l'url ci dessous pour réinitialisez votre mdp : " . $url;


        $nom = Utilisateur::select("nom")->where("email", "=", $mail)->first()->nom;

        // envoie du mail
        EmailFactory::envoieEmail($body, "Réinitialisez le mdp", $mail, $nom);

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
