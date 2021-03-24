<?php

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Capsule\Manager as DB;
use ppil\models\Utilisateur;

class TestUser extends TestCase
{
    protected function setUp(): void
    {
        $db = new DB();
        $db->addConnection([
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'port'      => '3307',
            'database'  => 'test',
            'username'  => 'root',
            'password'  => ''
        ]);

        $db->setAsGlobal();
        $db->bootEloquent();
    }

    public function test_insert()
    {
        $user = new Utilisateur();
        $user->email = 'email@email.com';
        $user->mdp = password_hash('mots de passe', PASSWORD_DEFAULT);
        $user->nom = 'Nom';
        $user->prenom = 'Prenom';
        $user->tel = '0102030405'; // Ce num ne marchera jamais dans la bdd
        $user->sexe = 'F';
        $user->a_voiture = 'O';
        $user->url_img = 'http://truc';
        $user->note = '9.9'; // Comment on fait pour re calulé la moyenne ?
        $user->activer_notif = 'O';

        $this->assertTrue($user->save());
    }

    public function test_verification()
    {
        $user = Utilisateur::where('email', '=', 'email@email.com')->first();
        $this->assertTrue(password_verify('mots de passe', $user->mdp));
        $this->assertEquals('Nom', $user->nom);
        $this->assertEquals('Prenom', $user->prenom);
        $this->assertEquals('0102030405', $user->tel);
        $this->assertEquals('F', $user->sexe);
        $this->assertEquals('O', $user->a_voiture);
        $this->assertEquals('http://truc', $user->url_img);
        $this->assertEquals('9.9', $user->note);
        $this->assertEquals('O', $user->activer_notif);
    }
}
