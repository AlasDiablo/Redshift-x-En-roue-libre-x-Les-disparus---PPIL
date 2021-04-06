<?php
	public static function getRide($id)
	{
		$trajet = Trajet::where('id_trajet', '=', $id)->first();
		return $trajet;
	}
?>
