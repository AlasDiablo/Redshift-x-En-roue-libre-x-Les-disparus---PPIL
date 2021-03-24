<?php

namespace ppil\controller;

use ppil\models\Utilisateur;
use ppil\util\AppContainer;

class UserController  extends Controller
{
	public function creerUtilisateur(){
		$nom = filter_var($_POST['name'], FILTER_DEFAULT);
		$prenom = filter_var($_POST['firstName'], FILTER_DEFAULT);
		$email = filter_var($_POST['mail'], FILTER_DEFAULT);
		$mdp = filter_var($_POST['password'], FILTER_DEFAULT);
		$mdpconf = filter_var($_POST['confirmpassword'], FILTER_DEFAULT);
		$tel = filter_var($_POST['phone'], FILTER_DEFAULT);
		$sexe = filter_var($_POST['sex'], FILTER_DEFAULT);
		$a_voiture = filter_var($_POST['car'], FILTER_DEFAULT);

		if(!isset($nom) || !preg_match("#^[a-zA-Z]+$#", $nom) || strlen($nom < 2) || strlen($nom > 25)){
			return UserView::erreurPost();
		}

		if(!isset($prenom) || !preg_match("#^[a-zA-Z]+$#", $prenom) || strlen($prenom < 2) || strlen($prenom > 25)){
			return UserView::erreurPost();
		}

		if(!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL){
			return UserView::erreurPost();
		}

		if(!isset($mdp) || !isset($mdpconf) || ($mdp != $mdpconf) || strlen($mdp) < 7 || strlen($mdpconf) < 7){
			return UserView::erreurPost();
		}

		if(!isset($tel) || strlen($tel) != 10 || !preg_match("#[0][6][- \.?]?([0-9][0-9][- \.?]?){4}$#", $tel)){
			return UserView::erreurPost();
		}

		if(!isset($sexe)){
			return UserView::erreurPost();
		}

		if(!isset($a_voiture)){
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
		
		$_SESSION['mail'] = $mail;

        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
	}
}