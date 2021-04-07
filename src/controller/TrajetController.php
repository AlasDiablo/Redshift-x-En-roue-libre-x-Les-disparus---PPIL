<?php

namespace ppil\controller;
use Exception;
use ppil\models\Trajet;
use ppil\models\VilleFrance;
use ppil\util\AppContainer;
use ppil\view\TrajetView;

class TrajetController
{
	public static function creerTrajet(){
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
		if(!isset(VilleFrance::where('ville_nom', '=', $villeDepart)->first()->ville_nom)){
			return TrajetView::erreurPost("La ville de départ n'existe pas dans la base de données.");
		}
		
		#Messages d'erreurs pour la ville d'arrivée
		if (!isset($villeArrivee)){
            return TrajetView::erreurPost("Vous n'avez pas mis de ville d'arrivée.");
        }
		if(preg_match('/^[a-zA-Z]+$/', $villeArrivee, $matches, PREG_OFFSET_CAPTURE, 0) == false){
            return TrajetView::erreurPost("Le nom de la ville d'arrivée ne peut pas comporter de chiffre.");
        }
		if(!isset(VilleFrance::where('ville_nom', '=', $villeArrivee)->first()->ville_nom)){
			return TrajetView::erreurPost("La ville d'arrivée n'existe pas dans la base de données.");
		}
		
		#Messages d'erreurs pour la date de départ
		if (!isset($date)){
            return TrajetView::erreurPost("Vous n'avez pas mis de date de départ.");
        }
		if(!TrajetController::validateDateDepart($date, "Y-m-d")){
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
		if(!TrajetController::validateDateDepart($date . " " . $heureDepart, "Y-m-d hh:mm")){
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
		
		#Messages d'erreurs pour les etapes intermédiaires
		if (isset($etapeInter) && $etapeInter!=""){
			if(preg_match('/^[a-zA-Z]+$/', $etapeInter, $matches, PREG_OFFSET_CAPTURE, 0) == false){
				return TrajetView::erreurPost("Le nom d'une étape intermédiaire: " . $etapeInter . " ne peut pas comporter de chiffre.");
			}
			if(!isset(VilleIntermediaire::where('ville', '=', $etapeInter)->first()->ville_nom)){
				return TrajetView::erreurPost("L'étape intermédiaire: " . $etapeInter . " n'existe pas dans la base de données.");
			}
		}
		
		
		$ride = new Trajet();
        $ride->date = $date;
		$ride->ville_depart = $villeDepart;
		$ride->ville_arrivee = $villeArrivee;
		$ride->heure_depart = $heureDepart;
		$ride->email_conducteur = $_SESSION['mail'];
		$ride->nbr_passager = $nbPassagers;
		$ride->prix = $prix;
		$ride->save();

        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
	}
	
	private static function validateDateDepart($date, $format)
	{
		$date_now = new \DateTime();
		return strtotime($date_now->format($format)) <= strtotime($date) ;
	}
	
}
