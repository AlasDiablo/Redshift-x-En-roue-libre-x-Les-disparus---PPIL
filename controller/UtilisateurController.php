<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UtilisateurController extends Controller
{
	/**
     * Affiche la liste de tous les utilisateur
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $users = DB::select('select * from utilisateur', [1]);

        return view('utilisateur.email', ['users' => $users]);
    }
	
	public function create($email, $mdp, $nom, $prenom, $tel, $sexe, $a_voiture){
		/* htmlentities();
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			
		}		
		else{
			
		}*/
		DB::insert('insert into utilisateur (email, mdp, nom, prenom, tel, sexe, a_voiture, note, activer_notification) values (?, ?)', [$email, $mdp, $nom, $prenom, $tel, $sexe, $a_voiture, 5, 'V', ]);
	}
}