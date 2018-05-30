<?php

namespace Src\Controller;
use Src\Model\PostModel;
use Src\Model\CommentModel;
use Symfony\Component\HttpFoundation\Request;
Use \Exception;
use Src\Classes\FlashBag;

class HuntController
{

	public function show(Application $app)
	{

		$postModel = new HuntModel();
		/*$authors = $postModel->listAllAuthors();
		$category = $postModel->listAllCategory();
		$posts = $postModel->listAll();
		$flashBag = new FlashBag();*/

		$array = [ 'posts' => $posts, 'authors' => $authors, 'category' => $category , 'flashBag' => $flashBag ];

		return $app['twig']->render('addPost.twig',$array);
	}

	public function Add(Application $app, Request $request)
	{

		$title = $request->get("title");
		$contents = $request->get("contents");
		$author = $request->get("author");
		$url_img = $request->get("url");
		$category = $request->get("category");

		$postModel = new PostModel();
		$baseUrl = $app["request"]->getBaseUrl();

		try{
			$postModel->save($title, $url_img, $contents,$author, $category);
		}
		catch(Exception $e){
			$flashBag = new FlashBag();
			$flashBag->add($e->getMessage());

			return $app->redirect($baseUrl."/admin/addPost");
		}

		return $app->redirect($baseUrl);
	}

}
