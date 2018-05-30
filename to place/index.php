<?php

/*================  Sources needed ===============*/
//include "../vendor/autoload.php";

/*================  Instantiation ===============*/
//On initialise le timeZone
ini_set('date.timezone', 'Europe/Paris');

//On ajoute l'autoloader
$loader = require_once __DIR__ . '/../vendor/autoload.php';

//dans l'autoloader nous ajoutons notre répertoire applicatif
$loader->add("App",dirname(__DIR__));

$app = new Silex\Application();
$app['debug'] = true;
$twig = new Silex\Provider\TwigServiceProvider();
$app->register($twig, [ 'twig.path' => 'View' ]);
//Old URL GENERATOR
//$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
//New URL GENERATOR
$app->register(new Silex\Provider\RoutingServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
//Exemple
//$app['url_generator']->generate('example');


/*================  Main Code ===============*/
require __DIR__.'/../app/bootstrap.php';



//Méthode GET
//$app->get('/','Src\Controller\HomeController::Show')->bind('home');
//$app->get('/','Src\Classes\Database::queryOne')->bind('home');
/*$app->get('/category/{id}','Src\Controller\CategoryController::show');
$app->get('/author/{id}','Src\Controller\AuthorController::show');
$app->get('/admin/removePost/{id}','Src\Controller\RemovePostController::erase');
$app->get('/admin','Src\Controller\AdminController::Show');*/

//Méthode GET et POST
/*$app->get('/article/{id}','Src\Controller\PostController::show')->bind('post');
$app->post('/post/{id}','Src\Controller\PostController::Add');

$app->get('/admin/addPost','Src\Controller\AddPostController::Show');
$app->post('/admin/addPost','Src\Controller\AddPostController::Add');

$app->get('/admin/addCategory','Src\Controller\AddCategoryController::Show');
$app->post('/admin/addCategory','Src\Controller\AddCategoryController::Add');

$app->get('/admin/addAuthor','Src\Controller\AddAuthorController::Show');
$app->post('/admin/addAuthor','Src\Controller\AddAuthorController::Add');*/

//Ajout d'une variable globale avec accés dans tous les fichiers
//$app['twig']->addGlobal('patate',42);
//Utilisation de la variable patate dans un fichier twig avec {{patate}}

//Gestion de la page d'erreur, on peut passer par un controller ou retourner la page error.twig directement
//$app->error(function(){return "error 404";});
