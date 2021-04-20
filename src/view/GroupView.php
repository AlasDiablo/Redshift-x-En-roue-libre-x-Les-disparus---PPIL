<?php


namespace ppil\view;


use ppil\models\Utilisateur;
use ppil\util\AppContainer;

class GroupView
{
    public static function supprimerAmiGroupe($id): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/suppAmisGroupe.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('group-delete_post', array('id' => $id));
        $template = str_replace('${post_url}', $urlPost, $template);

        $urlParent = $app->getRouteCollector()->getRouteParser()->urlFor('group', array('id' => $id));
        $template = str_replace('${parent}', $urlParent, $template);

        return ViewRendering::render($template, 'Supprimer un ami');
    }

    public static function ajouterAmiGroupe($id): string
    {
        $app = AppContainer::getInstance();
        $template = file_get_contents('./html/ajoutAmisGroupe.html');

        $urlPost = $app->getRouteCollector()->getRouteParser()->urlFor('group-add_post', array('id' => $id));
        $template = str_replace('${post_url}', $urlPost, $template);

        $urlParent = $app->getRouteCollector()->getRouteParser()->urlFor('group', array('id' => $id));
        $template = str_replace('${parent}', $urlParent, $template);

        return ViewRendering::render($template, 'Ajouter un ami');
    }

    public static function renderList($groups)
    {
        $out = '';
        foreach ($groups as $group) {
            $template = file_get_contents('./html/group/case.html');
            $template = str_replace('${title}', $group->nom, $template);
            $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('group', array('id' => $group->id_groupe));
            $template = str_replace('${url}', $url, $template);
            $out .= $template;
        }

        $templateView = file_get_contents('./html/list-group.html');
        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('group-create');
        $templateView = str_replace('${create_group}', $url, $templateView);
        $templateView = str_replace('${list_group}', $out, $templateView);

        return ViewRendering::render($templateView, 'Mes Groupes');
    }

    public static function renderGroupe(array $data)
    {
        $app = AppContainer::getInstance();
        $templateView = file_get_contents('./html/group/details.html');
        $templateView = str_replace('${name}', $data['createur']->nom, $templateView);
        $templateView = str_replace('${firstname}', $data['createur']->prenom, $templateView);
        $templateView = str_replace('${title}', $data['nom'], $templateView);

        if ($_SESSION['mail'] == $data['createur']->email) {
            $addMember = $app->getRouteCollector()->getRouteParser()->urlFor('group-add', array('id' => $data['id']));
            $deleteMember = $app->getRouteCollector()->getRouteParser()->urlFor('group-delete', array('id' => $data['id']));
            $html = <<<html
<button class="btn btn-outline-info" onclick="addMember()">Ajouter un membre</button>
<button class="btn btn-outline-danger" onclick="deleteMember()">Supprimer un membre</button>
<script>
const addMember = () => {
    location.replace("$addMember")
};
const deleteMember = () => {
    location.replace("$deleteMember")
};
</script>
html;
            $templateView = str_replace('${admin}', $html, $templateView);
        } else {
            $templateView = str_replace('${admin}', '', $templateView);
        }

        $membersList = '';
        foreach ($data['membres'] as $membreID) {
            $membre = Utilisateur::where('email', '=', $membreID->email_membre)->first();
            $template = file_get_contents('./html/group/member.html');
            $template = str_replace('${firstname}', $membre->prenom, $template);
            $template = str_replace('${name}', $membre->nom, $template);
            $url_img = isset($membre->url_img) ? $membre->url_img : '/uploads/default';
            $template = str_replace('${avatar}', $url_img, $template);
            $membersList .= $template;
        }

        $templateView = str_replace('${member_list}', $membersList, $templateView);

        return ViewRendering::render($templateView, 'Groupe - ' . $data['nom']);
    }

    public static function createGroup() {
        $template = file_get_contents('./html/creerGroupe.html');
        $template = str_replace('${post_url}', AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('group-create_post'), $template);
        return ViewRendering::render($template, 'Créer un groupe d\'amie');
    }
}