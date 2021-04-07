<?php


namespace ppil\view;


class RideView
{
    public static function renderUser($data)
    {
        $template = file_get_contents('./html/detailsTrajet.html');

        $template = str_replace('${ville_depart}', $data['ville_depart'], $template);

        $template = str_replace('${ville_arrivee}', $data['ville_arrivee'], $template);

        $template = str_replace('${nbr_passager}', $data['nbr_passager'], $template);

        $template = str_replace('${nbr_passager_occup}', $data['nbr_passager_occup'], $template);

        $template = str_replace('${heure_depart}', $data['heure_depart'], $template);

        $template = str_replace('${prix}', $data['prix'], $template);

        $template = str_replace('${lieuxRDV}', $data['lieuxRDV'], $template);

        $template = str_replace('${commentaires}', $data['commentaires'], $template);

        $ville_intermediere = '';
        foreach ($data['ville_intermediere'] as $datum) {
            $ville_intermediere .= '<li>' . $datum->ville . '</li>';
        }

        $passagers = '';
        foreach ($data['passagers'] as $datum) {
            $passagers .= '<li>' . $datum->prenom . ' ' . $datum->nom . '</li>';
        }

        $template = str_replace('${ville_intermediere}', $ville_intermediere, $template);

        $template = str_replace('${passagers}', $passagers, $template);

        return ViewRendering::render($template, 'Trajet - ' . $data['ville_depart'] . ' - ' . $data['ville_arrivee']);
    }
}