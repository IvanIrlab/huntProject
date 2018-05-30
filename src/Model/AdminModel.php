<?php

namespace Src\Model;
Use Src\Classes\Database;
Use \DomainException;
Use \InvalidArgumentException;

class AdminModel{
	private $database;

	public function __construct(){
        $this->database = new Database();
	}

	public function signup($firstName,$lastName,$email,$password,$address,$city,$zipCode,$country,$phone){

		if(empty($email)){
            throw new DomainException("WARNING --> L'adresse e-mail doit être renseigné!<br>");
		}

		if(strlen($password) < 8){
            throw new DomainException("WARNING --> Veuillez entrer le mot de passe avec au moins 8 caractères!<br>");
		}

		$this->emailExist($email);

		$sql = 'INSERT INTO admin (firstName, lastName, email, password, address, city, zipCode, country, phone, creationTimestamp)
        	VALUES(?,?,?,?,?,?,?,?,?,NOW())';

		$passwordHashed = $this->hashPassword($password);

        $array = [$firstName,$lastName,$email,$passwordHashed,$address,$city,$zipCode,$country,$phone];

		$result = $this->database->executeSql($sql,$array);
		return $result;
	}

	private function emailExist($email){

		$sql = 'SELECT id
	    		FROM Admin
	    		WHERE email = ?';

		$exist = $this->database->queryOne($sql,[$email]);

		if(!empty($exist)){
            throw new DomainException("WARNING --> L'e-mail existe déjà dans la BDD!<br>");
		}
	}

	public function login($email, $password){

//Méthode 1 (Avec foreach, $infos a plusieurs users)
		/*$sql = 'SELECT Email, Password
	    		FROM User';

		$infos = $this->database->query($sql);
		var_dump($infos);

		foreach($infos AS $database){
			if((stristr($database['Email'], $email) === FALSE)
			&& (stristr($database['Password'], $password) === FALSE)) {
				//echo 'email non trouvé dans la chaîne de caractères';
			}
			else{
				echo 'email trouvé!!! Bravo';
        		return true;
			}
		}
		return false;*/

//Méthode 2 (Sans foreach, $infos a le user cherché ou pas(vide))

		if(empty($email)){
            throw new DomainException("WARNING --> L'adresse e-mail doit être renseigné!<br>");
			return false;
		}

		if(empty($password)){
            throw new DomainException("WARNING --> Veuillez entrer le mot de passe!<br>");
			return false;
		}

		$sql = 'SELECT id, firstName, lastName, email, password
	    		FROM Admin
	    		WHERE email = ?';

		$infos = $this->database->queryOne($sql,[$email]);

		if(empty($infos)){
            throw new DomainException("WARNING --> L'e-mail n'existe pas dans la BDD!<br>");
		}
		$crypt = $this->verifyPassword($password,$infos['password']);

		if(!$crypt){
            throw new DomainException("WARNING --> Connexion failed!<br>");
			//echo 'email ou mot de passe non trouvés dans BDD';
		}

		return $infos;
	}

  // prend 1 param : un mot de passe
  // retourne le mot de passe hashé (= encrypté)
  private function hashPassword($password) {
      // doc : php.net/manual/fr/faq.passwords.php
      $salt = '$2y$11$'.substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);

      return crypt($password, $salt);
  }

  // prend 2 param : un mot de passe en claire et un mot de passe hashé
  // retourne true si c'est le meme mot de passe
  private function verifyPassword($password, $hashedPassword) {
      return crypt($password, $hashedPassword) == $hashedPassword;
  }
}
