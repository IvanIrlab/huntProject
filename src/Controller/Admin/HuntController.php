<?php

namespace Src\Controller\Admin;

Use \Exception;
use Silex\Application;
use Src\Classes\FlashBag;
use Src\Classes\AdminSession;
use Src\Model\HuntModel;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\JsonResponse;

class HuntController
{

	public function AddChallenge(Application $app, Request $request, $id)
	{
		$admin_active =  new AdminSession($app); //launch session
		$admin_id = $admin_active->getUserId();
		$flashBag = new FlashBag();

		if(!$admin_active->isAuthenticated()){
				$flashBag->add("Attention vous devez vous connecter pour pouvoir créer une chasse!!!");

				$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$spots = NULL;

		try{
			$huntModel = new HuntModel();

			$hunt_id = $id;
			$spot_id = $request->get('select-place');
			//var_dump($spot_id);
			$huntModel->saveChallenge($request->get('hunt-id'),$request->get('select-place'),$request->get('qcm-question'),$request->get('qcm-choix-1'),$request->get('qcm-choix-2'),$request->get('qcm-choix-3'),$request->get('qcm-choix-4'),$request->get('qcm-response'),$request->get('qru-question'),$request->get('qru-response'));


			$hunts = $huntModel->listAll();
			$hunt = $huntModel->listOne($hunt_id);
			$spots = $huntModel->listAllSpots($hunt_id);
			$challengesQcm = $huntModel->listAllChallengesQcm($hunt_id);
			$challengesQru = $huntModel->listAllChallengesQru($hunt_id);

			$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots, 'id' => $hunt_id, 'challengesQcm' => $challengesQcm, 'challengesQru' => $challengesQru];


			return $app['twig']->render('Admin/hunt.twig',$array);
		}

		catch(DomainException $e){
				$flashBag->add($e->getMessage());
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots, 'response' => $response,'id' => $hunt_id];
		return $app['twig']->render('Admin/hunt.twig',$array);
		//return $app->redirect($app['url_generator']->generate("home"));
	}

	public function AddSpot(Application $app, Request $request, $id)
	{

		$admin_active =  new AdminSession($app); //launch session
		$admin_id = $admin_active->getUserId();
		$flashBag = new FlashBag();

		if(!$admin_active->isAuthenticated()){
				$flashBag->add("Attention vous devez vous connecter pour pouvoir créer une chasse!!!");

				$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$spots = NULL;

		try{
			$huntModel = new HuntModel();

			$hunt_id = $id;
			$count = $huntModel->countSpots($hunt_id);
			$hunts = $huntModel->listAll();
			$hunt = $huntModel->listOne($hunt_id);
			$spots = $huntModel->listAllSpots($hunt_id);
			$challengesQcm = $huntModel->listAllChallengesQcm($hunt_id);
			$challengesQru = $huntModel->listAllChallengesQru($hunt_id);

			$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots, 'id' => $hunt_id, 'challengesQcm' => $challengesQcm, 'challengesQru' => $challengesQru];

			if($count[0]["COUNT(*)"] == 12){
				$flashBag->add("ATTENTION vous avez atteint le nombre maximal d'étape (12)!");
							var_dump("ATTENTION vous avez atteint le nombre maximal d'étape (12)!");
				return $app['twig']->render('Admin/hunt.twig',$array);
			}

			$huntModel->saveSpot($request->get('place-name'), $request->get('place-coord-lat'), $request->get('place-coord-long'),
			NULL,
			$request->get('place-coord-rad'),
			$request->get('picture-name'),
			$request->get('desc'), $hunt_id);

			return $app['twig']->render('Admin/hunt.twig',$array);
		}

		catch(DomainException $e){
				$flashBag->add($e->getMessage());
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots, 'response' => $response,'id' => $hunt_id];
		return $app['twig']->render('Admin/hunt.twig',$array);
		//return $app->redirect($app['url_generator']->generate("home"));
	}

	public function Delete(Application $app, $id)
	{
		$admin_active =  new AdminSession($app); //launch session
		$flashBag = new FlashBag();

		if(!$admin_active->isAuthenticated()){
				$flashBag->add("Attention vous devez vous connecter pour pouvoir réserver!!!");
				$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$huntModel = new HuntModel();
		$huntModel->delete($id);
		$hunts = $huntModel->listAll();
		$challengesQcm = $huntModel->listAllChallengesQcm($hunt_id);
		$challengesQru = $huntModel->listAllChallengesQru($hunt_id);
		$hunt = NULL;
		$spots = NULL;

		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'id' => $hunt_id, 'spots' => $spots, 'challengesQcm' => $challengesQcm, 'challengesQru' => $challengesQru];

		//return $app['twig']->render('Admin/createHunt.twig',$array);
		return $app->redirect($app['url_generator']->generate("createHunt"));
	}

	public function DeleteChallengeQcm(Application $app, $id)
	{
		$admin_active =  new AdminSession($app); //launch session
		$flashBag = new FlashBag();

		if(!$admin_active->isAuthenticated()){
				$flashBag->add("Attention vous devez vous connecter pour pouvoir réserver!!!");
				$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$huntModel = new HuntModel();
		$hunt_id = $huntModel->getHuntIdFromChallenges($id,'Qcm');
		$hunt_id = $hunt_id[0]['hunt_id'];
		$huntModel->deleteChallengeQcm($id);
		$hunts = $huntModel->listAll();
		$hunt = $huntModel->listOne($hunt_id);
		$spots = $huntModel->listAllSpots($hunt_id);
		$challengesQcm = $huntModel->listAllChallengesQcm($hunt_id);
		$challengesQru = $huntModel->listAllChallengesQru($hunt_id);

		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots, 'id' => $hunt_id, 'challengesQcm' => $challengesQcm, 'challengesQru' => $challengesQru];


		return $app['twig']->render('Admin/hunt.twig',$array);
		//return $app->redirect($app['url_generator']->generate("createHunt"));
	}

	public function DeleteChallengeQru(Application $app, $id)
	{
		$admin_active =  new AdminSession($app); //launch session
		$flashBag = new FlashBag();

		if(!$admin_active->isAuthenticated()){
				$flashBag->add("Attention vous devez vous connecter pour pouvoir réserver!!!");
				$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$huntModel = new HuntModel();
		$hunt_id = $huntModel->getHuntIdFromChallenges($id,'Qru');
		$hunt_id = $hunt_id[0]['hunt_id'];
		$huntModel->deleteChallengeQru($id);
		$hunts = $huntModel->listAll();
		$hunt = $huntModel->listOne($hunt_id);
		$spots = $huntModel->listAllSpots($hunt_id);
		$challengesQcm = $huntModel->listAllChallengesQcm($hunt_id);
		$challengesQru = $huntModel->listAllChallengesQru($hunt_id);

		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots, 'id' => $hunt_id, 'challengesQcm' => $challengesQcm, 'challengesQru' => $challengesQru];

		//return $app->redirect($basePath . '/nigmahunt/web/index.php/admin/createHunt/');
		return $app['twig']->render('Admin/hunt.twig',$array);
		//return $app->redirect($app['url_generator']->generate("createHunt"));
	}

	public function Manage(Application $app, Request $request, $id)
	{

				$admin_active =  new AdminSession($app); //launch session
				$flashBag = new FlashBag();

				if(!$admin_active->isAuthenticated()){
						$flashBag->add("Attention vous devez vous connecter pour pouvoir réserver!!!");
						$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
						return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
				}

				$huntModel = new HuntModel();
				$hunt = $huntModel->listOne($id);
				$hunts = $huntModel->listAll();
				$spots = $huntModel->listAllSpots($id);
				$challengesQcm = $huntModel->listAllChallengesQcm($id);
				$challengesQru = $huntModel->listAllChallengesQru($id);

				$response = new JsonResponse();
		    $response->setContent(json_encode(array('spots' => $spots), JSON_NUMERIC_CHECK));


				//json_encode($spots, JSON_FORCE_OBJECT);

				/*$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $response];*/
				//return $app['twig']->render('Admin/createHunt.twig',$array);
				$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots, 'id' => $id, 'challengesQcm' => $challengesQcm, 'challengesQru' => $challengesQru];

				return $app['twig']->render('Admin/hunt.twig',$array);
	}

	public function Show(Application $app)
	{

		$admin_active =  new AdminSession($app); //launch session
		$flashBag = new FlashBag();

		if(!$admin_active->isAuthenticated()){
				$flashBag->add("Attention vous devez vous connecter pour pouvoir réserver!!!");
				$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$hunt = new HuntModel();
		$hunts = $hunt->listAll();
		$hunt = NULL;
		$spots = NULL;

		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots];
		return $app['twig']->render('Admin/createHunt.twig',$array);
		//return $app->redirect($app['url_generator']->generate("home"));
	}

	public function Add(Application $app, Request $request)
	{

		$admin_active =  new AdminSession($app); //launch session
		$admin_id = $admin_active->getUserId();
		$flashBag = new FlashBag();

		if(!$admin_active->isAuthenticated()){
				$flashBag->add("Attention vous devez vous connecter pour pouvoir créer une chasse!!!");

				$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$spots = NULL;

		try{
			$huntModel = new HuntModel();
			$huntModel->save($request->get('hunt-title'), $request->get('place-name'), $request->get('place-coord-lat'), $request->get('place-coord-long'), $request->get('place-address'),$admin_id);

			$hunt_id = $huntModel->getLastHunt();

			$huntModel->saveSpot($request->get('place-name'), $request->get('place-coord-lat'), $request->get('place-coord-long'),
			NULL,
			$request->get('place-coord-rad'),
			$request->get('picture-name'),
			$request->get('desc'), $hunt_id['id']);

			$hunts = $huntModel->listAll();
			$hunt = $huntModel->listOne($id);

			$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots];
			return $app['twig']->render('Admin/createHunt.twig',$array);
		}

		catch(DomainException $e){
				$flashBag->add($e->getMessage());
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $spots];
		return $app['twig']->render('Admin/createHunt.twig',$array);
		//return $app->redirect($app['url_generator']->generate("home"));
	}

	public function AjaxShowSpot(Application $app, Request $request, $id)
	{

		$admin_active =  new AdminSession($app); //launch session
		$flashBag = new FlashBag();

		if(!$admin_active->isAuthenticated()){
				$flashBag->add("Attention vous devez vous connecter pour pouvoir réserver!!!");
				$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active];
				return $app['twig']->render('home.twig',$array);//Redirige vers la page d'accueil'
		}

		$huntModel = new HuntModel();
		$hunt = $huntModel->listOne($id);
		$hunts = $huntModel->listAll();
		$spots = $huntModel->listAllSpots($id);

		$response = new JsonResponse();
    $response->setContent(json_encode(array('spots' => $spots), JSON_NUMERIC_CHECK));


		//json_encode($spots, JSON_FORCE_OBJECT);

		/*$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $response];*/
		//return $app['twig']->render('Admin/createHunt.twig',$array);
		return $response;
	}

//Request From Mobile (IOS and ANDROID)
	public function AjaxMobileHunt(Application $app, Request $request)
	{

		$huntModel = new HuntModel();
		$hunts = $huntModel->listAll();
		//$spots = $huntModel->listAllSpots($id);

		$response = new JsonResponse();
		$response->setContent(json_encode(array('hunts' => $hunts), JSON_NUMERIC_CHECK));
//var_dump($hunts);

		//json_encode($spots, JSON_FORCE_OBJECT);

		/*$array = ['flashBag' => $flashBag, 'admin_active' => $admin_active, 'hunts' => $hunts, 'hunt' => $hunt, 'spots' => $response];*/
		//return $app['twig']->render('Admin/createHunt.twig',$array);
		return $response;
	}
}
