<?php


namespace ppil\view;


use ppil\models\Utilisateur;
use ppil\util\AppContainer;
use ppil\models\VilleIntermediaire;

class RideView
{
    public static function renderRide($data)
    {
        $template = file_get_contents('./html/detailsTrajet.html');

        $template = str_replace('${ville_depart}', $data['ville_depart'], $template);

        $template = str_replace('${ville_arrivee}', $data['ville_arrivee'], $template);

        $template = str_replace('${nbr_passager}', $data['nbr_passager'], $template);

        $template = str_replace('${nbr_passager_occup}', $data['nbr_passager_occup'], $template);

        $template = str_replace('${heure_depart}', $data['heure_depart'], $template);

        $template = str_replace('${prix}', $data['prix'], $template);

        $template = str_replace('${date}', $data['date'], $template);

        $template = str_replace('${lieuxRDV}', $data['lieuxRDV'], $template);

        $template = str_replace('${commentaires}', $data['commentaires'], $template);

        if ($data['creator'] == $_SESSION['mail']) {
            $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('delete-ride', array('id' => $data['id']));
            $out = <<<html
<button type="button" class="btn btn-outline-danger" onclick="location.replace('$url')">Supprimer</button>
html;
            $template = str_replace('${button}', $out, $template);
        } else {
            $tmp = array();
            foreach ($data['passagers'] as $passager) array_push($tmp, $passager->email_passager);
            if (in_array($_SESSION['mail'], $tmp, false)) {
                $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('dismiss-ride', array('id' => $data['id']));
                $out = <<<html
<button type="button" class="btn btn-outline-danger" onclick="location.replace('$url')">Annulé ma participation</button>
html;
                $template = str_replace('${button}', $out, $template);
            } else {
                $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('ride-participated', array('id' => $data['id']));
                $out = <<<html
<button type="button" class="btn btn-outline-info" onclick="location.replace('$url')">Participé au trajet</button>
html;
                $template = str_replace('${button}', $out, $template);
            }
        }

        $ville_intermediere = '';
        foreach ($data['ville_intermediere'] as $datum) {
            $ville_intermediere .= '<li>' . $datum->ville . '</li>';
        }
        if ($ville_intermediere == '') $ville_intermediere .= '<li>Aucune étape intérmediaires n\'a été indiquée</li>';

        $passagers = '';
        foreach ($data['passagers'] as $datum) {
            $user = Utilisateur::where('email', '=', $datum->email_passager)->first();
            $passagers .= '<li>' . $user->prenom . ' ' . $user->nom . '</li>';
        }
        if ($passagers == '') $passagers .= '<li>Aucun passager n\'a été trouvé</li>';

        $template = str_replace('${ville_intermediere}', $ville_intermediere, $template);

        $template = str_replace('${passagers}', $passagers, $template);

        return ViewRendering::render($template, 'Trajet - ' . $data['ville_depart'] . ' - ' . $data['ville_arrivee']);
    }

    public static function renderCreate()
    {
        if (!isset($_SESSION['mail'])) return UserView::erreurPost('Forbidden');
        if (Utilisateur::where('email', '=', $_SESSION['mail'])->first()->a_voiture == 'O') {
            $app = AppContainer::getInstance();
            $template = file_get_contents('./html/creerTrajet.html');

            $template = str_replace('${post_url}', $app->getRouteCollector()->getRouteParser()->urlFor('create-ride_post'), $template);

            return ViewRendering::render($template, 'Créer un trajet');
        } else {
            return ViewRendering::render(file_get_contents('./html/creerTrajetSansVoiture.html'), 'Créer un trajet');
        }
    }

    public static function renderMinRide($rides) 
    {
        $app = AppContainer::getInstance();
        $out = '';
        foreach ($rides as $ride) {
            $template = file_get_contents('./html/caseTrajet.html');
            $template = str_replace('${ville_depart}', $ride->ville_depart, $template);

            $template = str_replace('${ville_arrivee}', $ride->ville_arrivee, $template);

            $template = str_replace('${nbr_passager}', $ride->nbr_passager, $template);

            $template = str_replace('${details}', $app->getRouteCollector()->getRouteParser()->urlFor('ride', array('id' => $ride->id_trajet)), $template);

            $template = str_replace('${date}', $ride->date, $template);

            $template = str_replace('${heure_depart}', $ride->heure_depart, $template);

            $ville_intermediere = '';
            $ville = VilleIntermediaire::where('id_trajet', '=', $ride->id_groupe)->get();
            foreach ($ville as $datum) {
                $ville_intermediere .= '<li>' . $datum->ville . '</li>';
            }
            if ($ville_intermediere == '') $ville_intermediere .= '<li>Aucune étape intérmediaires n\'a été indiquée</li>';

            $template = str_replace('${ville_intermediere}', $ville_intermediere, $template);

            $out .= $template;
        }
        return $out;
    }

    public static function renderRideList($data, $page_title, $title)
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/trajetListe.html');

        $template = str_replace('${list_trajet}', self::renderMinRide($data), $template);

        $template = str_replace('${title}', $title, $template);

        $template = str_replace('${create_ride}', $app->getRouteCollector()->getRouteParser()->urlFor('create-ride'), $template);

        return ViewRendering::render($template, $page_title);
    }
}