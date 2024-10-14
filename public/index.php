<?php

ini_set("display_errors",1);

use Router\Router; // On importe Router car c'est qu'on a ecrit dans le composer
use  Core\Exceptions\NotFoundException;
use  Core\Model;
require "../vendor/autoload.php";
define('VIEWS',dirname(__DIR__).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR);
define('SCRIPTS',dirname($_SERVER['SCRIPT_NAME']).DIRECTORY_SEPARATOR);
define('ASSETS',dirname(__DIR__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR);
// Les Variable constante de connection
define('DB_HOST','localhost');
define('DB_NAME','portfolio');
define('DB_USER','root');
define('DB_PASS','');
define('HTDOCS',basename(dirname(__DIR__)));
// config.php
define('ERROR_LOG_PATH', '/'.HTDOCS.'/logs/site_errors.log');
define('SQL_LOG_PATH', '/'.HTDOCS.'/logs/sql_queries.log');




$router=new Router($_GET['url']);
// Roouter pour la welcome
$router->get('/','User\Controllers\UserController@welcome');


$router->get('/admin','User\Controllers\UserController@listUser');
$router->get('/admin/create','User\Controllers\UserController@createUser');

$router->post('/admin','User\Controllers\UserController@loginStaffPost');
$router->post('/admin/create','User\Controllers\UserController@createUserPost');
// Router Pour ouvrir la page de modification d'un Utilisateur
$router->get('/admin/edit/:id','User\Controllers\UserController@editUser');

// Router Pour ouvrir la page de modification d'un Utilisateur
$router->get('/admin/view/:id','User\Controllers\UserController@viewUser');
// Router Pour ouvrir la page Pour mettre a jour les données d'un Utilisateur
$router->post('/admin/edit/:id','User\Controllers\UserController@updateUser');
// Router Pour ouvrir la page Pour mettre a jour les données d'un Utilisateur
$router->post('/admin/delete/:id','User\Controllers\UserController@destroyUser');

// Router Pour afficher la page index des posts (les derniers articles)
//$router->get('/cards','App\Controllers\BlogController@index');


// Rediriger vers la page de connection
$router->get('/login','User\Controllers\UserController@login');
// Valider le formulaire de connection
$router->post('/login','User\Controllers\UserController@loginPost');

// Rediriger vers la page d'inscription
$router->get('/signUp','User\Controllers\UserController@signUp');
// Valider le formulaire de connection
$router->post('/signUp','User\Controllers\UserController@signUpPost');



// se deconnecter
$router->get('/logout','User\Controllers\UserController@logout');

// On essai d'executer le router si erreur on renvoi la page 404 personnaliser
try{ $router->run();}catch(NotFoundException $e){return $e->error404();}
















?>