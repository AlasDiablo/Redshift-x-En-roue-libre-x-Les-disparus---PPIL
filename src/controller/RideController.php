<?php
	public static function getRide($id)
	{
		$trajet = Trajet::where('id_trajet', '=', $id)->first();
		return $trajet;
	}
	
	public static function getEtape($id)
	{
		$etapes = Ville_intermediaire::where('id_trajet', '=', $id)->get();
		return $etapes;
	}
	
	
	public static function getPassager($id)
	{
		$passagers = Passager::where('id_trajet', '=', $id)->get();
		return $passagers;
	}
	
	public static function getNbPlaceOccupee($id)
	{
		$nbPassager = count(getPassager($id));
		return $nbPassager;
	}
	
?>
