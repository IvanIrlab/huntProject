<?php

use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;
use app\Controller;
use Silex\ControllerProviderInterface;
use Src\Classes\Database;
use Silex\Application;
// Home page
$app = new Silex\Application();

$app->get('/', function (Application $app) {
  //$test = new Database();
    //var_dump("HELLO");
    
    return $app['twig']->render('../views/addHunt.twig');
});

//On ajoute l'autoloader
//$app->mount("/", new app\Controller\HomeController());
$app->get("/", 'app\Controller\HomeController::Show');
//$app->get("/", function() {
  //return 'Hello world';});

$app->run();
