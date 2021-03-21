<?php

namespace ppil\controller;

use ppil\models\Utilisateur;
use ppil\util\AppContainer;

class UserController
{
    public static function seConnecter()
    {
        $mail = filter_var($_POST['mail'], FILTER_DEFAULT);
        $mdp = filter_var($_POST['mdp'], FILTER_DEFAULT);

        $value = Utilisateur::where('email', '=', $mail)->get()->first();
        if (!isset($value)) {
            return UserView::erreurPost();
        }

        $bddMdp = Utilisateur::select('mdp')->where('email', '=', $mail)->first()->mdp;
        if (!password_verify($mdp, $bddMdp)) {
            return UserView::erreurPost();
        }

        $_SESSION['mail'] = $mail;
        $url = AppContainer::getInstance()->getRouteCollector()->getRouteParser()->urlFor('root');
        header("Location: $url");
        exit();
    }

    public static function seDeconnecter()
    {
    }

    public static function mdpOublie()
    {
    }
}