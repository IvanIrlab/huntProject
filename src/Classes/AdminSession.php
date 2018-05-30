<?php

namespace Src\Classes;

class AdminSession
{

	public function __construct($app) {
		$this->app = $app;
	}

	// crée la session
	// on veux stocker l'id, le prenom, et le nom
    public function create($adminId, $firstName, $lastName) {
        if(session_status() == PHP_SESSION_NONE)
        {
            //session_start();
						$this->app['session']->start();
        }
				$this->app['session']->set('adminId',  $adminId);
				$this->app['session']->set('firstName', $firstName);
				$this->app['session']->set('lastName',  $lastName);
    	/*$_SESSION['adminId'] = $adminId;
    	$_SESSION['firstName'] = $firstName;
    	$_SESSION['lastName'] = $lastName;*/
    }

	// returne true si la session existe, sinon false
    public function isAuthenticated() {

      //if(array_key_exists('adminId', $_SESSION)) {
			if($this->app['session']->has('adminId')) {
				return true;
			}
			else {
				return false;
			}
    }

	// supprime la session
    public function destroy() {
    	//session_destroy();
			$this->app['session']->clear();
    	/*unset($_SESSION['adminId']);
    	$_SESSION['firstName'] = "";
    	$_SESSION['lastName'] = "";*/
			$this->app['session']->remove('adminId');
			$this->app['session']->remove('firstName');
			$this->app['session']->remove('lastName');
    }


	// retourne le prenom si la session existe
 	// sinon retourne null
	public function getFirstName() {

		if($this->isAuthenticated()) {
			return $this->app['session']->get('firstName');
			}
			else {
				return null;
			}
    }

	// retourne le nom complet (prenom + nom) si la session existe
	// sinon retourne null
	public function getLastName() {

		if($this->isAuthenticated()) {
			return $this->app['session']->get('lastName');
			}
			else {
				return null;
			}
    }

	// retourne l'id si la session existe
	// sinon retourne null
	public function getUserId() {

		if($this->isAuthenticated()) {
			return $this->app['session']->get('adminId');
		}
		else {
			return null;
		}
    }
}
