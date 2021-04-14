<?php

use ppil\controller\GroupController;
use ppil\controller\ListController;
use ppil\controller\NotificationController;
use ppil\controller\RideController;
use ppil\controller\UserController;
use ppil\util\AppContainer;
use ppil\view\GroupView;
use ppil\view\RideView;
use ppil\view\UserView;
use ppil\view\IndexView;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Illuminate\Database\Capsule\Manager as DB;

require __DIR__ . '/vendor/autoload.php';

$ini_file = parse_ini_file('src/conf/conf.ini');
$db = new DB();
$db->addConnection([
    'driver' => $ini_file['driver'],
    'host' => $ini_file['host'],
    'port' => $ini_file['port'],
    'database' => $ini_file['database'],
    'username' => $ini_file['username'],
    'password' => $ini_file['password']
]);

session_start();

$db->setAsGlobal();
$db->bootEloquent();

// Creation de l'application slim
$app = AppContainer::getInstance();
$app->addRoutingMiddleware();

// Affichage des erreur (Dev Only)
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// --------------------- Creation de l'index (page pricipale) ---------------------
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write(IndexView::render());
    return $response;
})->setName('root');


// --------------------- Creation d'un compte ---------------------
// Get (obtenir la page web)
$app->get('/account/sign-up', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserView::creerUnCompte());
    return $response;
})->setName('sign-up');
// Post géré les donnée entré par l'utilisateur
$app->post('/account/sign-up', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserController::creerUtilisateur());
    return $response;
})->setName('sign-up_post');


// --------------------- Connexion a un compte ---------------------
// Get (obtenir la page web)
$app->get('/account/sign-in', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserView::seConnecter());
    return $response;
})->setName('sign-in');
// Post géré les donnée entré par l'utilisateur
$app->post('/account/sign-in', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserController::seConnecter());
    return $response;
})->setName('sign-in_post');

// --------------------- Se deconnecté ---------------------
$app->get('/account/logout', function (Request $request, Response $response, $args) {
    UserController::seDeconnecter();
    return $response;
})->setName('logout');

// --------------------- Mot de passe oublié ---------------------
// Get (obtenir la page web)
$app->get('/account/password-forgotten', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserView::motDePasseOublie());
    return $response;
})->setName('password-forgotten');
// Post géré les donnée entré par l'utilisateur
$app->post('/account/password-forgotten', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserController::mdpOublie());
    return $response;
})->setName('password-forgotten_post');

// Get formulaire de modification de mot de passe
$app->get('/account/password-forgotten/{key}', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserView::motDePasseOublieForm($args['key']));
    return $response;
})->setName('password-forgotten-key');
// Post formulaire de modification de mot de passe
$app->post('/account/password-forgotten/{key}', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserController::recupererMdp($args['key']));
    return $response;
})->setName('password-forgotten-key_post');

// --------------------- Mofifier mon profil ---------------------
// Get (obtenir la page web)
$app->get('/account/edit-profile', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserController::modifierProfilVue());
    return $response;
})->setName('edit-profile');
// Post géré les donnée entré par l'utilisateur
$app->post('/account/edit-profile', function (Request $request, Response $response, $args) {
    $response->getBody()->write(UserController::modifierUtilisateur());
    return $response;
})->setName('edit-profile_post');

// --------------------- Création d'un trajet ---------------------
$app->get('/ride/create', function (Request $request, Response $response, $args) {
    $response->getBody()->write(RideView::renderCreate());
    return $response;
})->setName('create-ride');

$app->post('/ride/create', function (Request $request, Response $response, $args) {
    RideController::creerTrajet();
    return $response;
})->setName('create-ride_post');

// --------------------- Consulter mes trajets ---------------------
// 
$app->get('/account/myrides', function (Request $request, Response $response, $args) {
    $response->getBody()->write(ListController::mesTrajets());
    return $response;
})->setName('myrides');

$app->get('/account/participating-rides', function (Request $request, Response $response, $args) {
    $response->getBody()->write(ListController::trajetsParticipes());
    return $response;
})->setName('participating-rides');

// --------------------- Consulter les trajets public ---------------------
//
$app->get('/ride/public', function (Request $request, Response $response, $args) {
    $response->getBody()->write(ListController::listPublic());
    return $response;
})->setName('public-ride');


// --------------------- Consulter les trajets public ---------------------
$app->get('/ride/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write(RideController::displayRide($args['id']));
    return $response;
})->setName('ride');

$app->get('/ride/{id}/participated', function (Request $request, Response $response, $args) {
    $response->getBody()->write(RideController::participate($args['id']));
    return $response;
})->setName('ride-participated');

// --------------------- Liste des notification ---------------------
$app->get('/account/notifications', function (Request $request, Response $response, $args) {
    $response->getBody()->write(NotificationController::renderNotificationsList());
    return $response;
})->setName('notifications');


// --------------------- Liste des groupe ---------------------
$app->get('/account/groups', function (Request $request, Response $response, $args) {
    $response->getBody()->write(GroupController::renderGroupList());
    return $response;
})->setName('groups');


// --------------------- Affiché un groupe groupe ---------------------
// Créer un groupe
$app->get('/account/group/create', function (Request $request, Response $response, $args) {
    $response->getBody()->write(GroupView::createGroup());
    return $response;
})->setName('group-create');

// Consulté un groupe
$app->get('/account/group/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write(GroupController::displayGroupe($args['id']));
    return $response;
})->setName('group');

// Ajouté un membre
$app->get('/account/group/{id}/add', function (Request $request, Response $response, $args) {
    $response->getBody()->write(GroupView::ajouterAmiGroupe($args['id']));
    return $response;
})->setName('group-add');

// Suprimé un membre
$app->get('/account/group/{id}/delete', function (Request $request, Response $response, $args) {
    $response->getBody()->write(GroupView::supprimerAmiGroupe($args['id']));
    return $response;
})->setName('group-delete');

// Créer un groupe
$app->post('/account/group/create', function (Request $request, Response $response, $args) {
    $response->getBody()->write(GroupController::creerGroupe());
    return $response;
})->setName('group-create_post');

// Ajouté un membre
$app->post('/account/group/{id}/add', function (Request $request, Response $response, $args) {
    $response->getBody()->write(GroupController::addMember($args['id']));
    return $response;
})->setName('group-add_post');

// Suprimé un membre
$app->post('/account/group/{id}/delete', function (Request $request, Response $response, $args) {
    $response->getBody()->write(GroupController::deleteMember($args['id']));
    return $response;
})->setName('group-delete_post');

// Accepter une invitation à un groupe d'amis
$app->get('/account/group/{id}/accept', function (Request $request, Response $response, $args) {
    GroupController::acceptInvitation($args['id']);
    return $response;
})->setName('group-invit-accept');

// Refuser une invitation à un groupe d'amis
$app->get('/account/group/{id}/decline', function (Request $request, Response $response, $args) {
    GroupController::declineInvitation($args['id']);
    return $response;
})->setName('group-invit-decline');

// Demarais l'appliquation web
$app->run();