<?php

namespace Src\Controller;
use Silex\Application;
use Src\Model\PostModel;
Use \Exception;
use Src\Classes\FlashBag;
use Src\Classes\AdminSession;
use Symfony\Component\HttpFoundation\Request;

//use Silex\ServiceProviderInterface;
//use Symfony\Component\Routing\Generator\UrlGenerator;

class ContactController
{

	public function Show(Application $app)
	{
    $flashBag = '';
		$admin_active =  new AdminSession($app); //launch session
		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
		//var_dump($request->baseUrl);
		//var_dump($request->getBasePath());
		return $app['twig']->render('contact.twig',$array);
		//return $app->redirect($app['url_generator']->generate("home"));
	}

  public function Send(Application $app,  Request $request)
	{
    $flashBag = '';
		$admin_active =  new AdminSession($app); //launch session
		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
		//var_dump($request->baseUrl);
		//var_dump($request->getBasePath());
		return $app['twig']->render('contact.twig',$array);
		//return $app->redirect($app['url_generator']->generate("home"));
	}

}
