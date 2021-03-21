<?php

namespace ppil\controller;

use ppil\models\Utilisateur;
use ppil\util\AppContainer;

class UserController  extends Controller
{
	public function creerUtilisateur(){
		$nom = htmlentities($_POST['name']);
		$prenom = htmlentities($_POST['firstName']);
		$email = htmlentities($_POST['mail']);
		$mdp = htmlentities($_POST['password']);
		$tel = htmlentities($_POST['phone']);
		$sexe = htmlentities($_POST['sex']);
		$a_voiture = htmlentities($_POST['car']);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !filter_var($nom) || !filter_var($prenom) || !filter_var($mdp) || !filter_var($tel) || !filter_var($sexe) || !filter_var($a_voiture)){
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
			'activer_notification' => 'N',
		]);
		
		$_SESSION['mail'] = $mail;
        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();*/
	}

}