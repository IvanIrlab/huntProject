<?php

namespace Src\Controller\Admin;
use Silex\Application;
use Src\Model\AdminModel;
Use \Exception;
use Src\Classes\FlashBag;
use Src\Classes\AdminSession;
use Symfony\Component\HttpFoundation\Request;

//use Silex\ServiceProviderInterface;
//use Symfony\Component\Routing\Generator\UrlGenerator;

class AdminController
{

	public function Show(Application $app, Request $request)
	{
		$flashBag = '';
		$admin_active =  new AdminSession($app); //launch session
		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];

		return $app['twig']->render('Admin/admin.twig',$array);
		//return $app->redirect($app['url_generator']->generate("home"));
	}

	public function LoginPage(Application $app, Request $request)
	{
			$admin_active =  new AdminSession($app);
			$flashBag = new FlashBag();

			$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];

			return $app['twig']->render('Admin/login.twig',$array);//Redirige vers la page d'accueil'
	}

	public function Login(Application $app, Request $request)
	{

		$adminModel = new AdminModel();
		$flashBag = new FlashBag();

		try{
				$admin = $adminModel->login($request->get('pseudo'),$request->get('password'));

				$admin_active =  new AdminSession($app); //launch session

				if(!$admin_active->isAuthenticated()){

						$admin_active->create($admin['id'], $admin['firstName'], $admin['lastName']);

						$flashBag->add("Connexion ok!");
						$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];

						return $app['twig']->render('Admin/admin.twig',$array);//Redirige vers la page d'accueil'
				}
				else{

						$admin_active->destroy();
						$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];

						return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
				}
		}

		catch(DomainException $e){
				$flashBag->add($e->getMessage());
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}
		//return $app['twig']->render('Admin/admin.twig',$array);
		//return $app->redirect($app['url_generator']->generate("home"));
	}

	public function Logout(Application $app)
	{

			$admin_active = new AdminSession($app);
    	$admin_active ->destroy();

    	$flashBag = new FlashBag();
    	$flashBag->add("Déconnexion ok!");

			$admin_active->destroy();
			$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];

			return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'

	}


	public function Signup(Application $app, Request $request)
	{

    $flashBag = new FlashBag();
		$admin = new AdminModel();

		try{
				$admin->signup(
							$request->get('firstName'),
							$request->get('lastName'),
							$request->get('email'),
							$request->get('password'),
							$request->get('address'),
							$request->get('city'),
							$request->get('zipCode'),
							$request->get('country'),
							$request->get('phone')
							);

	      $flashBag->add("Inscription OK");
				$array = ['flashBag' => $flashBag];
	      return $app['twig']->render('home.twig',$array); //Redirige vers l'accueil'
				}

		catch(DomainException $e){
        $flashBag->add($e->getMessage());
        //On instancie la classe RegisterForm
				//$form = new RegisterForm();
        //Et on lie les valeurs du formulaire avec les champs paramétrés dans la classe
        //$form->bind($formFields);
        //On retourne le formulaire à la vue
        //return ['_form' => $form];
    	}
	}

}
