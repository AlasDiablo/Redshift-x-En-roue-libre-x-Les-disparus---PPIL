<?php

namespace ppil\controller;
use Exception;
use ppil\models\Trajet;
use ppil\models\VilleIntermediaire;
use ppil\util\AppContainer;

class TrajetController  extends Controller
{
	public function creerTrajet(){
		$villeDepart = filter_var($_POST['departure'], FILTER_DEFAULT);
		$villeArrivee = filter_var($_POST['arrival'], FILTER_DEFAULT);
		$date = filter_var($_POST['date'], FILTER_DEFAULT);
		$nbPassagers = filter_var($_POST['passengers'], FILTER_DEFAULT);
		$heureDepart = filter_var($_POST['hour'], FILTER_DEFAULT);
		$prix = filter_var($_POST['price'], FILTER_DEFAULT);
		
		//A changer en fonction de comment les etapes intermédiaires ont été intégré dans le formulaire (array...)
		$etapeInter = filter_var($_POST['stages'], FILTER_DEFAULT);
		
		// Lieu de rendez vous c'est quoi ? C4est meme pas dans la base de donées / j'en ai jamais entendu parler
		
		$commentaires = filter_var($_POST['comments'], FILTER_DEFAULT);
		
		$matches = null;
		
		#Messages d'erreurs pour la ville de départ
		if (!isset($villeDepart)){
            return TrajetView::erreurPost("Vous n'avez pas mis de ville de départ.");
        }
		if(preg_match('/^[a-zA-Z]+$/', $villeDepart, $matches, PREG_OFFSET_CAPTURE, 0) == false){
            return TrajetView::erreurPost("Le nom de la ville de départ ne peut pas comporter de chiffre.");
        }
		if(!isset(VilleIntermediaire::where('ville', '=', $villeDepart)->first()->ville)){
			return TrajetView::erreurPost("La ville de départ n'existe pas dans la base de données.");
		}
		
		#Messages d'erreurs pour la ville d'arrivée
		if (!isset($villeArrivee)){
            return TrajetView::erreurPost("Vous n'avez pas mis de ville d'arrivée.");
        }
		if(preg_match('/^[a-zA-Z]+$/', $villeArrivee, $matches, PREG_OFFSET_CAPTURE, 0) == false){
            return TrajetView::erreurPost("Le nom de la ville d'arrivée ne peut pas comporter de chiffre.");
        }
		if(!isset(VilleIntermediaire::where('ville', '=', $villeArrivee)->first()->ville)){
			return TrajetView::erreurPost("La ville d'arrivée n'existe pas dans la base de données.");
		}
		
		#Messages d'erreurs pour la date de départ
		if (!isset($date)){
            return TrajetView::erreurPost("Vous n'avez pas mis de date de départ.");
        }
		if(!validateDateDepart($date)){
			return TrajetView::erreurPost("Date de départ invalide.");
		}
		
		#Messages d'erreurs pour le nombre de passagers
		if(!isset($nbPassagers)){
			return TrajetView::erreurPost("Vous n'avez pas mis le nombre de passagers pour le trajet.");
		}
		if(preg_match('/^[1-9]+[0-9]*$/', $nbPassagers, $matches, PREG_OFFSET_CAPTURE, 0) == false){
            return TrajetView::erreurPost("Le nombre de passagers doit être un nombre entier.");
        }
		
		#Messages d'erreurs pour l'heure de départ
		if (!isset($heureDepart)){
            return TrajetView::erreurPost("Vous n'avez pas mis d'heure de départ.");
        }
		if(!validateDateDepart($date . " " . $heureDepart, "Y-m-d hh:mm")){
			return TrajetView::erreurPost("Heure de départ invalide.");
		}
		
		#Messages d'erreurs pour le nombre de passagers
		if(!isset($prix)){
			return TrajetView::erreurPost("Vous n'avez pas mis le nombre de passagers pour le trajet.");
		}
		if(!(filter_var($prix, FILTER_VALIDATE_FLOAT) || filter_var($prix, FILTER_VALIDATE_INT))){
			return TrajetView::erreurPost("Le prix doit être un nombre entier ou reel.");
        }
		if($prix < 0){
			return TrajetView::erreurPost("Le prix doit être supérieur ou egal à zero.");
		}
		
		if(!isset($etapeInter) || !preg_match("#^[a-zA-Z]+$#", $etapeInter)){
			return TrajetView::erreurPost();
		}
		#Messages d'erreurs pour les etapes intermédiaires
		if (isset($etapeInter)){
			if(preg_match('/^[a-zA-Z]+$/', $etapeInter, $matches, PREG_OFFSET_CAPTURE, 0) == false){
				return TrajetView::erreurPost("Le nom d'une étape intermédiaire: " . $etapeInter . " ne peut pas comporter de chiffre.");
			}
			if(!isset(VilleIntermediaire::where('ville', '=', $etapeInter)->first()->ville)){
				return TrajetView::erreurPost("L'étape intermédiaire: " . $etapeInter . " n'existe pas dans la base de données.");
			}
		}
		
		
		$trajet = Trajet::create([
			'date' => $date,
			'ville_depart' => $villeDepart,
			'ville_arrivee' => $villeArrivee,
			'heure_depart' => $heureDepart,
			'email_conducteur' => $_SESSION['mail'],
			'nbr_passager' => $nbPassagers,
			'prix' => $prix,
		]);

        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
	}
	
	function validateDateDepart($date, $format = "Y-m-d")
	{
		$d = DateTime::createFromFormat($format, $date);
		$date_now = new DateTime();
		return $d && $d->format($format) == $date && $date_now <= $d ;
	}
	
}
