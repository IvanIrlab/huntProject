<?php

/*================  Sources needed ===============*/
include "../vendor/autoload.php";
use Silex\Provider;
use Silex\Application;
// My classes
//use Src\Classes\Database;
//use Src\Classes\MyUtilities;
// My controllers
//use Src\Controller\HomeController;
//use Src\Controller\HuntController;
//use Src\Controller\Admin;
// RouteProvider.php
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
// request.php
use Symfony\Component\HttpFoundation\Request;

date_default_timezone_set('Indian/Reunion');



/*================  Instantiation ===============*/
$app = new Silex\Application();

$app['debug'] = true;

// < SILEX 2.0 (old)
//$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
//SILEX 2.0 (new)
$app->register(new Silex\Provider\RoutingServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(),['twig.path' => __DIR__.'/View']);
$app->register(new Silex\Provider\SessionServiceProvider());
$request = Request::createFromGlobals();
$basePath = $request->getBasePath();

//var_dump(Request::createFromGlobals()->getBasePath());
/*
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1',
    'assets.version_format' => '%s?version=%s',
    'assets.named_packages' => array(
        'base_path' => array('base_path' => $basePath)
        ,'css' => array('version' => 'css1', 'base_path' => $basePath.'/css'),
        'images' => array('base_urls' => $basePath.'/img'),
    ),
));
*/
/*================  Main Code ===============*/
//Méthode GET
$app->get('/','Src\Controller\HomeController::Show')->bind('home');
$app->get('/aboutUs','Src\Controller\HomeController::About')->bind('aboutUs');
$app->get('/contact','Src\Controller\ContactController::Show')->bind('contact');
$app->get('/admin','Src\Controller\Admin\AdminController::Show')->bind('admin');
$app->get('/admin/logout','Src\Controller\Admin\AdminController::Logout')->bind('Logout');
$app->get('/admin/createHunt','Src\Controller\Admin\HuntController::Show')->bind('creatHunt');
$app->get('/admin/createHunt/{id}','Src\Controller\Admin\HuntController::Manage')->bind('manage');
$app->get('/admin/loginPage','Src\Controller\Admin\AdminController::LoginPage')->bind('LoginPage');
$app->get('/admin/addChallenge/{id}','Src\Controller\Admin\HuntController::AddChallenge')->bind('AddGetChallenge');
$app->get('/admin/deleteHunt/{id}','Src\Controller\Admin\HuntController::Delete')->bind('deleteHunt');
$app->get('/admin/deleteChallengeQru/{id}','Src\Controller\Admin\HuntController::DeleteChallengeQru')->bind('deleteChallengeQru');
$app->get('/admin/deleteChallengeQcm/{id}','Src\Controller\Admin\HuntController::DeleteChallengeQcm')->bind('deleteChallengeQcm');

//$app->get('/mobile/hunt','Src\Controller\Admin\HuntController::AjaxMobileHunt')->bind('getMobileHunt');

//Méthode POST
$app->post('/contact/send','Src\Controller\ContactController::Send')->bind('contactSend');
$app->post('/admin/login','Src\Controller\Admin\AdminController::Login')->bind('Login');
$app->post('/admin/signup','Src\Controller\Admin\AdminController::Signup')->bind('Signup');
$app->post('/admin/createHunt','Src\Controller\Admin\HuntController::Add')->bind('addHunt');
$app->post('/admin/createHunt/{id}','Src\Controller\Admin\HuntController::Manage')->bind('manageHunt');
$app->post('/admin/addSpot/{id}','Src\Controller\Admin\HuntController::AddSpot')->bind('AddSpot');
$app->post('/admin/addChallenge/{id}','Src\Controller\Admin\HuntController::AddChallenge')->bind('AddChallenge');
$app->post('/admin/createSpot/{id}','Src\Controller\Admin\HuntController::AjaxShowSpot')->bind('addSpot');
$app->post('/admin/mobile/spot/{id}','Src\Controller\Admin\HuntController::AjaxShowSpot')->bind('showMobileSpots');
$app->post('/mobile/hunt','Src\Controller\Admin\HuntController::AjaxMobileHunt')->bind('getMobileHunts');

//Ajout d'une variable globale avec accés dans tous les fichiers
$app['twig']->addGlobal('basePath',$basePath);

//Gestion de la page d'erreur, on peut passer par un controller ou retourner la page error.twig directement
//$app->error(function(){return "error 404";});
/*var_dump(
crypt('root', '$2y$11$'.substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22)));*/

$app->run();
