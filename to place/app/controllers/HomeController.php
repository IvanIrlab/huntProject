<?php

namespace app\Controller;
use Silex\Application;
use Silex\ControllerProviderInterface;
/*Use \Exception;
use Src\Classes\FlashBag;
use Symfony\Component\HttpFoundation\Request;
use Silex\ServiceProviderInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;*/

class HomeController implements ControllerProviderInterface{

  public function connect(Application $app)
   {
     // créer un nouveau controller basé sur la route par défaut
//$index = $app['controllers_factory'];
//$index->match("/", 'App\Controller\IndexController::index')->bind("index.index");
         $controllers = $this->app['controllers_factory'];

         $controllers->get('/', [$this, 'Show'])->bind('home');
          return $controllers;
   }

  public function Show(Application $app)
	{
    var_dump("HELLO");
		/*$postModel = new PostModel();
		$posts = $postModel->listAll();

		$flashBag = new FlashBag();
		$array = [ 'posts' => $posts, 'flashBag' => $flashBag ];
		//var_dump($array);
    var_dump("Hello");*/
    return $app['twig']->render('../addHunt.twig');
		//return $app['twig']->render('addHunt.twig',$array);
		//return $app->redirect($app['url_generator']->generate("home"));
	}
}
